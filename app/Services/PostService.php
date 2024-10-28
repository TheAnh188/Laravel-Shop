<?php

namespace App\Services;

use App\Services\Interfaces\PostServiceInterface;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Repositories\Interfaces\RouteRepositoryInterface as RouteRepository;
// use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository ;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class PostService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $routeRepository;
    private $controller = 'PostController';
    // protected $postRepository;

    public function __construct(PostRepository $postRepository, RouteRepository $routeRepository)
    {
        $this->postRepository = $postRepository;
        $this->routeRepository = $routeRepository;
    }

    private function whereRaw($request, $language_id)
    {
        $rawCondition = [];
        if ($request->integer('post_catalogue_id') > 0) {
            //cau lenh truy van tim kiem bai viet thuoc loai danh muc nao
            //(truy van bong da thi se hien thi luon ca  luon ca bong da quoc te va bong da trong nuoc)
            $rawCondition['whereRaw'] =  [
                [
                    'tb3.post_catalogue_id IN (
                        SELECT id
                        FROM post_catalogues
                        JOIN post_catalogue_language ON post_catalogues.id = post_catalogue_language.post_catalogue_id
                        WHERE lft >= (SELECT lft FROM post_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM post_catalogues as pc WHERE pc.id = ?)
                        AND post_catalogue_language.language_id = '.$language_id.'
                    )',
                    [$request->integer('post_catalogue_id'), $request->integer('post_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }

    public function paginate($request, $language_id)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['tb2.language_id', '=', $language_id],
            ['posts.deleted_at', '=', NULL],
        ];

        $perpage = $request->integer('perpage');

        $posts = $this->postRepository->paginate(
            ['posts.id', 'posts.status', 'posts.image', 'posts.order', 'tb2.name', 'tb2.canonical'],
            $condition,
            $perpage,
            [
                'posts.id',
                'DESC',
            ],
            [
                'path' => '/post',
                'groupBy' => ['posts.id', 'posts.status', 'posts.image', 'posts.order', 'tb2.name', 'tb2.canonical'],
            ],
            [
                [
                    'post_language as tb2',
                    'tb2.post_id',
                    '=',
                    'posts.id',
                ],
                [
                    'post_catalogue_post as tb3',
                    'posts.id',
                    '=',
                    'tb3.post_id',
                ]
            ],
            ['post_catalogues'],
            $this->whereRaw($request, $language_id),

        );
        // dd($posts);die();
        return $posts;
    }

    private function formatPostPayload($request)
    {
        $postPayload = $request->only(['follow', 'status', 'image', 'album', 'post_catalogue_id']);
        $postPayload['album'] = isset($postPayload['album']) && !empty($postPayload['album']) ? json_encode($postPayload['album']) : NULL;
        $postPayload['user_id'] = Auth::id();
        return $postPayload;
    }

    private function formatPostLanguagePayload($request, $postId, $language_id)
    {
        $postLanguagePayload = $request->only(['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keyword', 'canonical']);
        $postLanguagePayload['canonical'] = str_replace(env('APP_URL'), '', $postLanguagePayload['canonical']);
        $postLanguagePayload['language_id'] = $language_id;
        $postLanguagePayload['post_id'] = $postId;
        return $postLanguagePayload;
    }

    private function formatPostCataloguePostPayload($request)
    {
        $postCataloguePostPayload = array_unique(array_merge($request->input('catalogue'), [$request->input('post_catalogue_id')]));
        return $postCataloguePostPayload;
    }

    public function create(StorePostRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->create($this->formatPostPayload($request));
            if ($post->id > 0) {
                $this->postRepository->createPivot($post, $this->formatPostLanguagePayload($request, $post->id, $language_id), 'languages');
                $post->post_catalogues()->sync($this->formatPostCataloguePostPayload($request));
                $this->routeRepository->create(
                    $this->formatRoutePayload(
                        $this->formatPostLanguagePayload($request, $post->id, $language_id)['canonical'],
                        $post->id,
                        $this->controller,
                        $language_id,
                    )
                );
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdatePostRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->findById($id);
            $result = $this->postRepository->update($id, $this->formatPostPayload($request));
            if ($result == true) {
                $post->languages()->detach([$this->formatPostLanguagePayload($request, $post->id, $language_id)['language_id'], $id]);
                $this->postRepository->createPivot($post, $this->formatPostLanguagePayload($request, $post->id, $language_id), 'languages');
                $post->post_catalogues()->sync($this->formatPostCataloguePostPayload($request));
                $condition = [
                    ['module_id', '=', $id],
                    ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller],
                ];
                $route = $this->routeRepository->findByCondition($condition);
                // dd($route);die();
                $this->routeRepository->update(
                    $route->id,
                    $this->formatRoutePayload(
                        $this->formatPostLanguagePayload($request, $post->id, $language_id)['canonical'],
                        $post->id,
                        $this->controller,
                        $language_id,
                    )
                );

            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->postRepository->delete($id);
            $this->routeRepository->deleteByCondition([
                ['module_id', '=', $id],
                ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller]
            ]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function setStatus($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = ($post['value'] == 1) ? 2 : 1;
            $this->postRepository->update($post['modelId'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatusAll($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $this->postRepository->updateByWhereIN('id', $post['id'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

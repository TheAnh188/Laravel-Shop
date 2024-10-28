<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Repositories\Interfaces\RouteRepositoryInterface as RouteRepository;
// use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository ;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $routeRepository;
    protected $nestedset;
    private $controller = 'PostCatalogueController';

    public function __construct(PostCatalogueRepository $postCatalogueRepository, RouteRepository $routeRepository)
    {
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->routeRepository = $routeRepository;
    }

    public function paginate($request, $language_id)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['tb2.language_id', '=', $language_id],
            ['post_catalogues.deleted_at', '=', NULL],
        ];

        $perpage = $request->integer('perpage');

        $postCatalogues = $this->postCatalogueRepository->paginate(
            ['post_catalogues.id', 'post_catalogues.status', 'post_catalogues.image', 'post_catalogues.level', 'post_catalogues.order', 'tb2.name', 'tb2.canonical'],
            $condition,
            $perpage,
            [
                'post_catalogues.lft',
                'ASC',
            ],
            ['path' => '/post-catalogue'],
            [
                [
                    'post_catalogue_language as tb2',
                    'tb2.post_catalogue_id',
                    '=',
                    'post_catalogues.id',
                ],
            ],
            [],
            [],

        );
        return $postCatalogues;
    }

    private function formatPostCataloguePayload($request)
    {
        $postCataloguePayload = $request->only(['parent_id', 'follow', 'status', 'image', 'album']);
        $postCataloguePayload['album'] = isset($postCataloguePayload['album']) && !empty($postCataloguePayload['album']) ? json_encode($postCataloguePayload['album']) : NULL;
        $postCataloguePayload['user_id'] = Auth::id();
        return $postCataloguePayload;
    }

    private function formatPostCatalogueLanguagePayload($request, $postCatalogueId, $language_id)
    {
        $postCatalogueLanguagePayload = $request->only(['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keyword', 'canonical']);
        $postCatalogueLanguagePayload['canonical'] = str_replace(env('APP_URL'), '', $postCatalogueLanguagePayload['canonical']);
        $postCatalogueLanguagePayload['language_id'] = $language_id;
        $postCatalogueLanguagePayload['post_catalogue_id'] = $postCatalogueId;
        return $postCatalogueLanguagePayload;
    }

    public function create(StorePostCatalogueRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $postCatalogue = $this->postCatalogueRepository->create($this->formatPostCataloguePayload($request));
            if ($postCatalogue->id > 0) {
                $this->postCatalogueRepository->createPivot($postCatalogue, $this->formatPostCatalogueLanguagePayload($request, $postCatalogue->id, $language_id), 'languages');
                $this->routeRepository->create(
                    $this->formatRoutePayload(
                        $this->formatPostCatalogueLanguagePayload($request, $postCatalogue->id, $language_id)['canonical'],
                        $postCatalogue->id,
                        $this->controller,
                        $language_id,
                    )
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'post_catalogues',
                    'foreignkey' => 'post_catalogue_id',
                    'language_id' => $language_id,
                ]);
            }
            $this->nestedSet($this->nestedset);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdatePostCatalogueRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $result = $this->postCatalogueRepository->update($id, $this->formatPostCataloguePayload($request));
            if ($result == true) {
                $postCatalogue->languages()->detach([$this->formatPostCatalogueLanguagePayload($request, $postCatalogue->id, $language_id)['language_id'], $id]);
                $this->postCatalogueRepository->createPivot($postCatalogue, $this->formatPostCatalogueLanguagePayload($request, $postCatalogue->id, $language_id), 'languages');
                $condition = [
                    ['module_id', '=', $id],
                    ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller],
                ];
                $route = $this->routeRepository->findByCondition($condition);
                $this->routeRepository->update(
                    $route->id,
                    $this->formatRoutePayload(
                        $this->formatPostCatalogueLanguagePayload($request, $postCatalogue->id, $language_id)['canonical'],
                        $postCatalogue->id,
                        $this->controller,
                        $language_id,
                    )
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'post_catalogues',
                    'foreignkey' => 'post_catalogue_id',
                    'language_id' => $language_id,
                ]);
                $this->nestedSet($this->nestedset);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy(int $id, $language_id)
    {
        DB::beginTransaction();
        try {
            $this->postCatalogueRepository->delete($id);
            $this->routeRepository->deleteByCondition([
                ['module_id', '=', $id],
                ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller]
            ]);
            $this->nestedset = new Nestedsetbie([
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' => $language_id,
            ]);
            $this->nestedSet($this->nestedset);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatus($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = ($post['value'] == 1) ? 2 : 1;
            $this->postCatalogueRepository->update($post['modelId'], $payload);
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
            $this->postCatalogueRepository->updateByWhereIN('id', $post['id'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

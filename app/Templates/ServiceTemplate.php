<?php

namespace App\Services;

use App\Services\Interfaces\{Module}ServiceInterface;
use App\Repositories\Interfaces\{Module}RepositoryInterface as {Module}Repository;
use App\Repositories\Interfaces\RouteRepositoryInterface as RouteRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Store{Module}Request;
use App\Http\Requests\Update{Module}Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class {Module}Service
 * @package App\Services
 */
class {Module}Service extends BaseService implements {Module}ServiceInterface
{
    protected ${module}Repository;
    protected $routeRepository;
    protected $nestedset;
    private $controller = '{Module}Controller';

    //Module = PostCatalogue
    //module = postCatalogue
    //tableName = post_catalogue
    //moduleCanonical = post-catalogue


    public function __construct({Module}Repository ${module}Repository, RouteRepository $routeRepository)
    {
        $this->{module}Repository = ${module}Repository;
        $this->routeRepository = $routeRepository;
    }

    public function paginate($request, $language_id)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['tb2.language_id', '=', $language_id],
            ['{tableName}s.deleted_at', '=', NULL],
        ];

        $perpage = $request->integer('perpage');

        ${module}s = $this->{module}Repository->paginate(
            ['{tableName}s.id', '{tableName}s.status', '{tableName}s.image', '{tableName}s.level', '{tableName}s.order', 'tb2.name', 'tb2.canonical'],
            $condition,
            $perpage,
            [
                '{tableName}s.lft',
                'ASC',
            ],
            ['path' => '/{moduleCanonical}'],
            [
                [
                    '{tableName}_language as tb2',
                    'tb2.{tableName}_id',
                    '=',
                    '{tableName}s.id',
                ],
            ],
            [],
            [],

        );
        return ${module}s;
    }

    private function format{Module}Payload($request)
    {
        ${module}Payload = $request->only(['parent_id', 'follow', 'status', 'image', 'album']);
        ${module}Payload['album'] = isset(${module}Payload['album']) && !empty(${module}Payload['album']) ? json_encode(${module}Payload['album']) : NULL;
        ${module}Payload['user_id'] = Auth::id();
        return ${module}Payload;
    }

    private function format{Module}LanguagePayload($request, ${module}Id, $language_id)
    {
        ${module}LanguagePayload = $request->only(['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keyword', 'canonical']);
        ${module}LanguagePayload['canonical'] = str_replace(env('APP_URL'), '', ${module}LanguagePayload['canonical']);
        ${module}LanguagePayload['language_id'] = $language_id;
        ${module}LanguagePayload['{tableName}_id'] = ${module}Id;
        return ${module}LanguagePayload;
    }

    public function create(Store{Module}Request $request, $language_id)
    {
        DB::beginTransaction();
        try {

            ${module} = $this->{module}Repository->create($this->format{Module}Payload($request));
            if (${module}->id > 0) {
                $this->{module}Repository->createPivot(${module}, $this->format{Module}LanguagePayload($request, ${module}->id, $language_id), 'languages');
                $this->routeRepository->create(
                    $this->formatRoutePayload(
                        $this->format{Module}LanguagePayload($request, ${module}->id, $language_id)['canonical'],
                        ${module}->id,
                        $this->controller,
                    )
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => '{tableName}s',
                    'foreignkey' => '{tableName}_id',
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

    public function update(int $id, Update{Module}Request $request, $language_id)
    {
        DB::beginTransaction();
        try {
            ${module} = $this->{module}Repository->findById($id);
            $result = $this->{module}Repository->update($id, $this->format{Module}Payload($request));
            if ($result == true) {
                ${module}->languages()->detach([$this->format{Module}LanguagePayload($request, ${module}->id, $language_id)['language_id'], $id]);
                $this->{module}Repository->createPivot(${module}, $this->format{Module}LanguagePayload($request, ${module}->id, $language_id), 'languages');
                $condition = [
                    ['module_id', '=', $id],
                    ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller],
                ];
                $route = $this->routeRepository->findByCondition($condition);
                $this->routeRepository->update(
                    $route->id,
                    $this->formatRoutePayload(
                        $this->format{Module}LanguagePayload($request, ${module}->id, $language_id)['canonical'],
                        ${module}->id,
                        $this->controller,
                    )
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => '{tableName}s',
                    'foreignkey' => '{tableName}_id',
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

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->{module}Repository->delete($id);
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
            $this->{module}Repository->update($post['modelId'], $payload);
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
            $this->{module}Repository->updateByWhereIN('id', $post['id'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\PostServiceInterface as PostService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\Gate;



class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $nestedset;
    protected $language;


    public function __construct(PostService $postService, PostRepository $postRepository){
        $this->middleware(function($request, $next){
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->initialize();
    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    }

    public function index(Request $request) {
        $canonicalPermission = 'post';
        $dropdown = $this->nestedset->Dropdown();
        $posts = $this->postService->paginate($request, $this->language);
        $model = 'post';
        return view("post/index", [
            'dropdown' => $dropdown,
            'posts' => $posts,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'post/create';
        $dropdown = $this->nestedset->Dropdown();
        return view("post/create", [
            'dropdown' => $dropdown,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StorePostRequest $request)
    {
        if($this->postService->create($request, $this->language)) {
            return redirect('/post')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/post')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'post/edit';
        $dropdown = $this->nestedset->Dropdown();
        $post = $this->postRepository->getPostById($id, $this->language);
        $album = json_decode($post->album);
        return view("post/edit", [
            'dropdown' => $dropdown,
            'post' => $post,
            'album' => $album,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdatePostRequest $request, string $id)
    {
        if($this->postService->update($id, $request, $this->language)) {
            return redirect('/post')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/post')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'post/delete';
        $postCatalogue = $this->postRepository->getPostById($id, $this->language);
        return view("post/delete", [
            'post'=> $postCatalogue,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->postService->destroy($id)) {
            return redirect('/post')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/post')->with('error', 'Đã xảy ra lỗi.');
    }
}

<?php

namespace App\Services;

use App\Services\Interfaces\SystemServiceInterface;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository ;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class SystemService
 * @package App\Services
 */
class SystemService implements SystemServiceInterface
{
    protected $systemRepository;

    public function __construct(SystemRepository $systemRepository){
        $this->systemRepository = $systemRepository;
    }

    private function encodeImageToBase64($image) {
        $base64String = null;
        if ($image instanceof \Illuminate\Http\UploadedFile && $image->isValid()) {
            $imageData = file_get_contents($image->getRealPath());
            $base64Image = base64_encode($imageData);
            $imageMimeType = $image->getMimeType();

            $base64String = "data:$imageMimeType;base64,$base64Image";
        }
        return $base64String;
    }

    public function save(Request $request, $language_id) {
        DB::beginTransaction();
        // try {
            $payload = $request->except('_token', 'image-filee');
            $payload['config']['language_id'] = $language_id;
            $payload['config']['homepage_favicon'] = $this->encodeImageToBase64($payload['config']['homepage_favicon']);
            $formattedPayload = [];
            if(count($payload['config'])) {
                foreach($payload['config'] as $key => $value) {
                    $formattedPayload[] = [
                        'keyword' => $key,
                        'content' => $value,
                        'language_id' => $language_id,
                        'user_id' => Auth::id(),
                    ];
                }
            }
            // dd($formattedPayload);

            $this->systemRepository->updateOrInsert($formattedPayload, ['keyword' => $key]);
            DB::commit();
            return true;
        // } catch(Exception $e) {
        //     DB::rollBack();
        //     echo $e->getMessage();
        //     return false;
        // }
    }

}

<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use App\Http\Requests\Upload\UploadAvatarRequest;
use Illuminate\Support\Facades\Storage;

class AvatarUploadController extends Controller
{
    public function store(UploadAvatarRequest $request)
    {
        $request->validated();
        if ($request->has('avatar')) {
            $path = $request->file('avatar')
                ->storeAs('avatars', $request->file('avatar')->hashName(), 'public');
            return [
                'url' => Storage::url($path),
                'name' => $request->file('avatar')->hashName(),
                'code' => '200',
                'message' => 'Image uploaded successfully'
            ];
        }
        return [
            'code' => '400',
            'message' => 'Something went wrong'
        ];
    }

    public function delete(UploadAvatarRequest $request)
    {
        $request->validated();
        if ($request['name'] !=null) {
            Storage::disk('public')->delete('/avatars/' . $request['name']);
            return [
                'code' => '200',
                'message' => 'Avatar delete success',
                'url'=>''
            ];
        }
        return [
            'code' => '404',
            'message' => 'Avatar not found',
            'url'=>''
        ];

    }
}

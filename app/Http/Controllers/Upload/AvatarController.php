<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
                                            'fileInput' => ['required', 'string'],
                                        ]);

        try {
            $base64String = $request->input('fileInput');

            // 1. Парсим base64 и проверяем тип
            if (!preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
                return response()->json(['error' => 'Неверный формат изображения'], 400);
            }

            $imageType = strtolower($matches[1]);
            if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'webp'])) {
                return response()->json(['error' => 'Недопустимый тип файла'], 400);
            }

            // 2. Декодируем
            $imageData = base64_decode(substr($base64String, strpos($base64String, ',') + 1));
            if (!$imageData || strlen($imageData) > 5 * 1024 * 1024) {
                return response()->json(['error' => 'Ошибка декодирования или файл слишком большой'], 400);
            }

            // 3. Генерируем имя и путь
            $fileName = Str::uuid() . '.' . $imageType;
            $filePath = 'avatars/' . $fileName;


            // 4. Сохраняем файл
            Storage::disk('public')->put($filePath, $imageData);

            // 5. Возвращаем URL
            return response()->json([
                                        'success' => true,
                                        'url' => Storage::disk('public')->url($filePath),
                                        'file_path' => $filePath
                                    ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
                                            'path' => ['required', 'string'],
                                        ]);

        try {
            $path = $request->input('path');

            // 4. Удаляем файл
            Storage::disk('public')->delete($path);

            return response()->json([
                                        'success' => true,
                                        'code' => 200,
                                        'message' => 'Avatar deleted successfully'
                                    ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
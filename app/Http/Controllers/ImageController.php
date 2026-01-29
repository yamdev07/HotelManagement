<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use App\Models\Room;
use App\Repositories\Interface\ImageRepositoryInterface;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function __construct(
        private ImageRepositoryInterface $imageRepository
    ) {}

    public function store(StoreImageRequest $request, Room $room)
    {
        // Créer le répertoire s'il n'existe pas
        $path = public_path('img/room/' . $room->number);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        
        $file = $request->file('image');

        // Upload de l'image
        $lastFileName = $this->imageRepository->uploadImage($path, $file);

        // Créer l'enregistrement Image
        Image::create([
            'room_id' => $room->id,
            'url' => $lastFileName,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('room.show', ['room' => $room->id])
            ->with('success', 'Image uploaded successfully!');
    }

    public function destroy(Image $image)
    {
        $path = public_path('img/room/' . $image->room->number . '/' . $image->url);
        
        if (file_exists($path)) {
            unlink($path);
        }
        
        $image->delete();

        return redirect()
            ->back()
            ->with('success', 'Image "' . $image->url . '" has been deleted!');
    }
}
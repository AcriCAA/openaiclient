<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Result; 

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Str;


class ResultController extends Controller
{
    //
    const MODEL = 'dall-e-3';
    const IMAGE_COUNT = 1;
    const IMAGE_SIZE = '1024x1024';
    public function show(Result $result){

        return view('results.show', ['result' => $result]); 

    }


    public function saveImage($result, $data, $image_name, $size){

     //associate the image with the result
        return $result->image()->create([

            'path' => $image_name ?? '',
            'type' => 'result_image', 
            'description' => $data["data"]["0"]["revised_prompt"] ?? '',
            'dimensions' => $size ?? '',
            'use' => 'result', 

        ]); 



    }




    public function submit(Request $request)
    {
        // Extracted constants for default values

        // Generate the image using a helper method
        $decodedResponse = $this->generateImage(
            $request->input('description')
        );

        // Create the result from the API response
        $result = Result::create([
            'api_model' => self::MODEL,
            'api_created_at' => $decodedResponse['created'] ?? '',
            'api_image_url' => $decodedResponse['data'][0]['url'] ?? '',
            'image_size' => self::IMAGE_SIZE,
            'original_prompt' => $request->input('description') ?? '',
            'revised_prompt' => $decodedResponse['data'][0]['revised_prompt'] ?? ''
        ]);

        // Store the image and associate it with the result
        $imageUrl = $decodedResponse['data'][0]['url'] ?? null;
        if ($imageUrl !== null) {
            $this->storeImage(
                $result,
                $decodedResponse,
                $imageUrl
            );
        }

        // Redirect to the result view
        return redirect()->route('result_show', ['result' => $result->id]);
    }

// Helper method for generating an image using the OpenAI API
    private function generateImage(string $description): array
    {
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/images/generations', [
                'model' => self::MODEL,
                'prompt' => $description,
                'n' => self::IMAGE_COUNT,
                'size' => self::IMAGE_SIZE,
            ]);


        return json_decode($response->getBody()->getContents(), true);
    }

// Helper method for storing the generated image
    private function storeImage(Result $result, array $decodedResponse, string $imageUrl): void
    {
        $imageFileName = Str::squish(self::MODEL . $decodedResponse['created'] . Str::take(
                    Str::squish($decodedResponse['data'][0]['revised_prompt']), 5
                )) . '.png';

        Storage::disk('public')->put($imageFileName, file_get_contents($imageUrl));

        $this->saveImage(
            $result,
            $decodedResponse,
            $imageFileName,
            self::IMAGE_SIZE
        );
    }
}

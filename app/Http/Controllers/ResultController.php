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




    public function submit(Request $request){

  //       curl https://api.openai.com/v1/images/generations \
  // -H "Content-Type: application/json" \
  // -H "Authorization: Bearer $OPENAI_API_KEY" \
  // -d '{
  //   "model": "dall-e-3",
  //   "prompt": "A sloth wearing sunglasses slowly walking down the middle of a busy street smiling. The style should be pixel art, like 80s and 90s video games.",
  //   "n": 1,
  //   "size": "1024x1024"
  // }'
        //hard coding these for now
        $api_model = 'dall-e-3';
        $n = 1; 
        $size = "1024x1024"; 


        $response = Http::withToken(config('services.openai.key'))
        ->post('https://api.openai.com/v1/images/generations', 

            [

                "model"=> $api_model,
                "prompt"=> $request->input('description'),
                "n"=> $n,
                "size"=> $size                    

            ]);


        $data = $response->getBody()->getContents();

        $data = json_decode($data, true); 

      

        

        

        $result = Result::create(
            [
                'api_model' => $api_model ?? '',
                'api_created_at' => $data["created"] ?? '',
                'api_image_url' => $data["data"]["0"]["url"] ?? '', 
                'image_size' => $size ?? '', 
                'original_prompt' => $request->input('description') ?? '', 
                'revised_prompt' => $data["data"]["0"]["revised_prompt"] ?? ''

            ]

        );

        //save result image
          if(null !== $data["data"]["0"]["url"]){

            $image_name = Str::squish($api_model.$data["created"].Str::take(Str::squish($data["data"]["0"]["revised_prompt"]), 5)); 


            Storage::disk('public')->put($image_name, file_get_contents($data["data"]["0"]["url"]));

            //passing image name as path 
            $image = $this->saveImage($result, $data, $image_name, $size);     

        }


        return redirect()->route('result_show',['result' => $result->id]);  
        

    }
}

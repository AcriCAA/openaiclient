<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use App\Models\Result; 


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
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

        // $url = $response->body(); 

        // return $response->json(); 

        

        // $data = $response->json();

        // $result = json_encode($data, true);

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




        return redirect()->route('result_show',['result' => $result->id]);  
        

    }
}

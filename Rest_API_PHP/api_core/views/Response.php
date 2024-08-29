<?php
date_default_timezone_set('America/Sao_Paulo');// para get da data/hora local
class Response 
{
    public static function transformar_json($status = 200, $message = 'success', $data = null)  {
        header('Content-Type: application/json');// para aceitar forma json
    
        // check if  API is active
        if(!API_IS_ACTIVE){
            return json_encode([
                'status' => 400,
                'message' => 'api is not running',
                'api_version' => API_IS_VERSION,
                "time_response" => time(),   // contagem em segundos desde 1970
                'datetime_response service' => date('d-m-Y H:i:s'),
                'data'=> null
            ], JSON_PRETTY_PRINT);//o uso do JSON_PRETTY_PRINT é opcional(ord)
        }else{

            return json_encode([
                'status' => $status,
                'message' => $message,
                'api_version' => API_IS_VERSION,
                "time_response" => time(),
                'datetime_response service' => date('Y-m-d H:i:s'),
                'data'=> $data     
            ],JSON_PRETTY_PRINT);
        }


    }

    

}


//'status' => $status,'message' => $message,'data'=> $data  
    
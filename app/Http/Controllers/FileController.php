<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function gerarCsv()
    {
        try
        {
            $con = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'));

            $aux = '';

            $linhas = file('complicadovet.sql'); // considerando arquivo na pasta public

            foreach ($linhas as $linha) 
            {
                if (substr($linha, 0, 2) == '--' || $linha == '' || substr($linha,0,2) == "/*")
                    continue;

                $aux .= $linha;

                if (substr(trim($linha), -1, 1) == ';')  
                {
                    mysqli_query($con, $aux);
                    $aux = '';
                }
            }

            $tables = ['cliente', 'animal'];
            mysqli_select_db($con, env('DB_DATABASE'));
            
            for($i = 0; $i < sizeof($tables); $i++)
            {                
                $result = mysqli_query($con, 'SELECT * FROM '.$tables[$i]);
                $fields = $result->fetch_fields();
                
                $head = array();
                foreach($fields as $key=>$field) {
                    $head[$key] = $field->name;
                }
                $file = fopen($tables[$i].'.csv', 'w');
                
                $row = $result->fetch_array(MYSQLI_NUM);

                if ($file && $result) 
                {
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename='.$tables[$i].'.csv');
                    header('Pragma: no-cache');
                    header('Expires: 0');
                    fputcsv($file, array_values($head), ";");

                    while ($row = $result->fetch_array(MYSQLI_NUM)) {
                        fputcsv($file, array_values($row),";");
                    }
                }

                fclose($file);
            }
            
            return response()->json('Arquivos criados na pasta public!', 200);
        }
        catch(\Exception $e)
        {
            return response()->json($e->getMessage(), 500);
        }
    }

    
    public function processarCsv(Request $request)
    {
        try
        {    
            if($request->hasFile('files'))
            {
                foreach($request->file('files') as $file)
                {
                    $nome = $file->hashName();

                    $original_name = $file->getClientOriginalName();

                    $file->store('uploads');
                                            
                    $file_handle = fopen(storage_path()."/app/uploads/".$nome, 'r');
                    $i = 0;

                    while(!feof($file_handle))
                    {
                        if($i == 0)
                            $headers = explode(";", fgetcsv($file_handle)[0]);
                        else
                        {
                            if($original_name == "animal.csv")
                            {
                                $line = $this->getLine($file_handle);
                                dd($line);
                            }
                            else
                            {
                                $line = $this->getLine($file_handle);
                                dd($line);
                            }
                        }
                        $i++;
                    }

                    fclose($file_handle);
                

                }
            }
            else
                return response()->json('Favor upar os arquivos!', 500);
        }
        catch(\Exception $e)
        {
            dd($e);
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getLine($handle)
    {
        return explode(";", fgetcsv($handle)[0]);
    }
}

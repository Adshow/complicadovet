<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Models\Contato;

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
                                
                                $pessoa = new Pessoa();

                                $pessoa->id = $line[0];
                                $pessoa->nome = $line[1];

                                $contato = $this->processaContato($line[2], $line[3], $line[4], $line[0]);

                                dd($contato);

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

    public function processaContato($telefone1, $telefone2, $email, $pessoa_id)
    {
        $contato = new Contato();

        $contato->pessoa_id = $pessoa_id;
        $dados1 = $this->tratarTelefone($telefone1);
        $dados2 = $this->tratarTelefone($telefone2);

        $contato->telefone_1 = $dados1['numero'];
        $contato->tipo_telefone_1 = $dados1['tipo'];

        $contato->telefone_2 = $dados2['numero'];
        $contato->tipo_telefone_2 = $dados2['tipo'];

        if($this->validarEmail($email))
            $contato->email = $email;
        else
            $contato->email = null;

        return $contato;
    }

    function mascara($mask, $str){

        $str = str_replace(" ","",$str);
    
        for($i=0;$i<strlen($str);$i++){
            $mask[strpos($mask,"#")] = $str[$i];
        }
    
        return $mask;
    
    }

    public function tratarTelefone($telefone)
    {
        $telefone = preg_replace("/[^0-9]/", "", $telefone); //remove não numéricos
        
        $telefone = ltrim($telefone, '0'); //ignora se o primeiro digito do ddd for 0
        
        if(strlen($telefone) == 10 || strlen($telefone) == 11) // ddd + telefone com 9 ou não
            $primeiro_digito = $telefone[2]; //primeiro digito apos ddd
        else
            $telefone = null; //telefone inválido
        
        if($telefone)
        {   
            if($primeiro_digito >=2 && $primeiro_digito <=5) //FIXO - 2 a 5
            {
                $tipotelefone = 'FIXO';
                $telefone = $this->mascara('(##)####-####', $telefone);
            }
            else if($primeiro_digito > 5) // CELULAR - 6 a 9
            {
                $tipotelefone = 'CELULAR';
                
                if(strlen($telefone) == 10) //sem o 9
                    $telefone = $this->mascara('(##)9####-####', $telefone);
                else
                    $telefone = $this->mascara('(##)#####-####', $telefone);
            }
            else //INVÁLIDO
            {
                $tipotelefone = null;
                $telefone = null;
            }
        }
        else
        {
            $tipotelefone = null;
            $telefone = null;
        }

        $dados = array();

        $dados['tipo'] = $tipotelefone;
        $dados['numero'] = $telefone;

        return $dados;
    }

    function validarEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

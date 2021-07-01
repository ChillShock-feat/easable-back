<?php
class User{
    /**
     * キー値とCRUDを取得
     * @param array $data
     * 
     */
    public function getKeyCrud($data){
        $keies = array();

        //キー値を配列に追加
        foreach($data as $key => $value){
            $keies[] = $key;
        }

        //配列の先頭の値を削除
        array_shift($keies);
        //配列の末尾の値を削除
        $crudHandle = array_pop($keies);

        return [$keies,$crudHandle];
    }

    /**
    * ファイル名作成
    * @param string $crudHandle
    * @return string $fileName
    */

    public function createFileName($crudHandle){
        $baseName = dirname(__FILE__)."/{$crudHandle}test";
        $fileName = $baseName;
        $i = 0;
        while(file_exists("{$fileName}.php")){
            $fileName = $baseName . '_' . $i;
            $i++;
            if($i>100){
                break;
            }
        }
        return $fileName;
    }

    /**
     * ユーザーの処理の保存先
     * @param string $fileName
     */

    public function createPath($fileName){
        //ユーザの処理Path作成
        $uri = $_SERVER['REQUEST_URI'];
        $user_handle_path = rtrim($uri,substr($uri, strrpos($uri, '/') + 1)).substr($fileName, strrpos($fileName, '/') + 1).".php";
        return $user_handle_path;
    }     

    /**
     * Insert Update Delete SelectのSQL文を作成
     * @param array $keies
     * @param string $crudHandle
     * @return string $sql
     */
    public function createSql($keies,$crudHandle){
        $column= "";
        $data = "";
        if ($crudHandle === "insert") {
            foreach($keies as $value){
                $column .= "{$value},";
                $data .= "\$_POST['$value'],";
            }
            
            //文字列の末尾を削除
            $column = rtrim($column,',');
            $data = rtrim($data,',');
            $sql = "\$sql = \"INSERT INTO {$_POST['table_name']} ({$column}) VALUES ({{$data}})\"";
        
        } else if ($crudHandle === "delete") {
            foreach($keies as $value){
                $column .= "{$value} = {\$_POST['$value']},";
            }
            
            //文字列の末尾を削除
            $column = rtrim($column,',');
            if($column !== ""){
                $sql = "\$sql = \"DELETE FROM {$_POST['table_name']} WHERE $column\"";
            }else{
                $sql = "\$sql = \"DELETE FROM {$_POST['table_name']}\"";
            }
        
        } else if ($crudHandle === "update") {
            foreach($keies as $value){
                $column .= "{$value} = \$_POST['$value'],";
            }
            
            //文字列の末尾を削除
            $column = rtrim($column,',');
            $sql = "\$sql = \"UPDATE {$_POST['table_name']} SET $column\"";
        } else if ($crudHandle === "select") {
            foreach($keies as $value){
                $column .= "{$value},";
                $data .= "\$_POST['$value'],";
            }
            //文字列の末尾を削除
            $column = rtrim($column,',');
            $data = rtrim($data,',');
            $sql = "\$sql = \"SELECT $column FROM {$_POST['table_name']}\"";
        }

        return $sql;
    }
   
}
<?php
    class MysqlLink
    {
        const NAMED = PDO::FETCH_NAMED;
        const NUM = PDO::FETCH_NUM;
        const RET_YES = 1;
        const RET_NO = 0;

        private $mo_link;
        private $ms_dbName;
        private $ms_characterSet;
        private $ms_mysqlSentence;

        public function __construct($host,$dbname,$charset,$user,$pass)
        {
            try
            {
            $this->mo_link = new PDO("mysql:host=".$host."; dbname=".$dbname."; charset=".$charset.";",$user,$pass);
            $this->mo_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->ms_dbName = $dbname;
            $this->ms_characterSet = $charset;
            $this->ms_mysqlSentence = "";
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Getters
        public function m_getCharacterSet()
        {
            return $this->ms_characterSet;
        }
        public function m_getMysqlSentence()
        {
            return $this->ms_mysqlSentence;
        }
        public function m_CreateNewConnection($host,$dbname,$charset,$user,$pass)
        {
             $this->mo_link = null;
             try
             {
             $this->mo_link = new PDO("mysql:host=".$host."; dbname=".$dbname."; charset=".$charset.";",$user,$pass);
             $this->mo_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             $this->ms_dbName = $dbname;
             $this->ms_characterSet = $charset;
             $this->ms_mysqlSentence = "";
             }
             catch(PDOException $e)
             {
                echo $e->getMessage();
             }
        }

        public function m_SendCommand($Command,$FetchMode,$Will_be_a_return_val_0_Will_not_be_a_return_val_1) //Si $Will_be_a_return_val_0_Will_not_be_a_return_val_1:0 -> ret: db info , else: number of rows affected by your script
        {
            if(!$Will_be_a_return_val_0_Will_not_be_a_return_val_1)
            {
                try
                {
                  $ln_count = $this->mo_link->exec($Command);
                  return $ln_count;
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
            else
            {
                try
                {
                    $stmt = $this->mo_link->prepare($Command);
                    $stmt->setFetchMode($FetchMode);
                    $stmt->execute();
                    return $stmt->fetchAll();
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
        }

        public function m_JSON_NAMED($DB_RES)
        {
           return '{"obj":' . json_encode($DB_RES) . '}';
        }


        public static function m_filterArray($input){
            foreach($input as $key => $value){
                $type = gettype($value);
                if($type === "array")
                   MysqlLink::m_filterArray($value);

                else
                    $value = MysqlLink::m_filterInput($value);
            }
        }

        public static function m_filterInput($str)//Convención de comillas dobles
        {
            $newStr = str_replace('"','',$str);
            return htmlspecialchars($newStr);
        }

        public function __destruct()
        {
            $this->mo_link = null;
            $this->ms_dbName = "";
            $this->ms_characterSet = "";
            $this->ms_mysqlSentence = "";
        }
    }
?>
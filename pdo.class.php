<?php
class DB {
    static private $instance;
    private $dbh;
    private function __construct(){
        $this->dbh = new PDO('mysql:host=111.231.106.198;dbname=account' , 'suishouji' , 'Hysj12)9-JiKmmy-#@!');
    }
//   /usr/local/nginx/conf/ssl/www.isoftware.xyz.crt    /usr/local/nginx/conf/ssl/www.isoftware.xyz.key
    private function __clone(){}

    static public function getInstance() {
        if (!self::$instance instanceof self) {
             self::$instance = new self();
        }
        return self::$instance;
     }

     // 获取单条记录
     public function getRow($sql , $params = [], $type=PDO::FETCH_ASSOC) {
         $sth = $this->dbh->prepare($sql);
         $sth->execute($params);
         return $sth->fetch($type);
     }

     // 获取所有记录
     public function getAll($sql,$params = []) {
         $sth = $this->dbh->prepare($sql);
         $sth->execute($params);
         return $sth->fetchAll();
     }

     // 新增插入
     public function insert($table , $data) {
        if ( empty($data) || !is_array($data) ) die('Data Can Not Be Empty');
        $temp = [];
        foreach ( $data as $k => $v ) {
            $temp[] = '?';
        }
        $sql = implode(',' , $temp);
        $sql = "INSERT INTO `$table` (" . implode(',',array_keys($data)) .") VALUES (" . implode(',' , $temp) . ")";
        $sth = $this->dbh->prepare($sql);
        $sth->execute(array_values($data));
        return $this->dbh->lastInsertId();
     }

     // 删除
     public function deleteRow($sql) {
        $result = $this->dbh->query($sql);
        if ($result) return true;
        return false;
     }

     // 更新修改
     public function update($table , $data) {
        if ( !$table || empty($data) ) die('Params Error');

        $sth_field = $this->dbh->query("SHOW COLUMNS FROM `$table`");
        $fields = $sth_field->fetchAll();

        $set = [];
        $sql = $where = '';
        foreach ( $data as $k => $v) {

            foreach ( $fields as $fk => $fv) {
                if ($k == $fv['Field']) {
                    if ($fv['Key'] == 'PRI') {
                        $where = " WHERE `{$k}` = " . (is_numeric($v) ? $v : "'{$v}'");
                        unset($data[$k]);
                        continue;
                    }
                    $sql .= "`{$k}` = ?,";
                    $set[] = $v;
                    //$set[$k] = is_numeric($v) ? $v : "'{$v}'";
		    break;
                }
            }
        }

        $sql = "UPDATE `{$table}` SET " . rtrim($sql , ',') . $where;
        $sth = $this->dbh->prepare($sql);
        return $sth->execute($set);
     }
} // Class End

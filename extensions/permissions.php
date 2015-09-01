<?php


class permissions extends extender{
  public $roleID;

  public function __construct(){
    parent::__construct();
    $this->checkUserRole();
    if(!$this->checkUrlRights()){
      system::prePrintArray('You do not have the required permissions.');
      die();
    }

  }

  private function checkUserRole(){
    if(isset($_SESSION['user'])){
      $this->db->queryRow('SELECT id FROM permissions_roles WHERE name = :name',
                          array(':name'=>'Administrator'));
      $this->roleID = $this->db->return['id'];
    } else {
      $this->db->queryRow('SELECT id FROM permissions_roles WHERE name = :name',
                          array(':name'=>'Guest'));
      $this->roleID = $this->db->return['id'];
    }
  }

  private function checkUrlRights(){
    $con = system::request()[0];
    if(isset(system::request()[1])){
      $req = system::request()[1];
    } else {
      $req = '';
    }
    if(empty($con) && empty($req)){
      return TRUE;
    }
    $query = 'SELECT * FROM permissions_rights r
                INNER JOIN permissions_roles_rights rr
                  ON r.id = rr.right_id
              WHERE ((
                      r.controller = :con AND
                      r.request = :req AND
                      r.permitted = 1 AND
                      rr.permitted = 1
                    )
                    OR
                    (
                      r.controller = :con AND
                      r.request = :req AND
                      r.permitted = 0 AND
                      rr.permitted = 0
                    )
                    OR
                    (
                      r.controller = :con AND
                      r.request = "*" AND
                      r.permitted = 1 AND
                      rr.permitted = 1
                    ))
                    AND rr.role_id = :roleid
              ';
    $this->db->queryData($query, array(':con'=>$con,
                              ':req'=>$req,
                              ':roleid'=>$this->roleID
                        ));
    $rights = $this->db->return;
    if(count($rights) > 0){
      foreach($rights as $right){
        if($right['permitted'] == 0){
          return FALSE;
        }
      }
    } else {
      return FALSE;
    }
    return TRUE;
  }

  public function checkFunctionRights(){

    $con = '';
    $req = '';

    $query = 'SELECT * FROM permissions_rights r
                INNER JOIN permissions_roles_rights rr
                  ON r.id = rr.right_id
              WHERE ((
                  r.controller = :con AND
                  r.request = :req AND
                  r.permitted = 1 AND
                  rr.permitted = 1
                )
                OR
                (
                  r.controller = :con AND
                  r.request = :req AND
                  r.permitted = 0 AND
                  rr.permitted = 0
                )
                OR
                (
                  r.controller = :con AND
                  r.request = "*" AND
                  r.permitted = 1 AND
                  rr.permitted = 1
                  )
                ) AND rr.role_id = :roleid
              ';
    $this->db->queryData($query, array(':con'=>$con,
                              ':req'=>$req,
                              ':roleid'=>$this->roleID
                        ));
    $rights = $this->db->return;
    if(count($rights) > 0){
      foreach($rights as $right){
        if($right['permitted'] == 0){
          return FALSE;
        }
      }
    } else {
      return FALSE;
    }

    return TRUE;
  }

}


?>

<?php

class page_controller{
    public $dao_kat;
    
    public function __construct()
    {
        $this->dao_kat = new dao_kat();
    }

    public function invoke()
    {
        //print_r($_SERVER);
        $result=$this->dao_kat->select();
        if(isset($_SERVER['HTTP_ACCEPT'])){
            $type=$_SERVER['HTTP_ACCEPT'];
            if(strpos($type,'text/html')!== false){

                //header http accept: 'text/html'
                
                // lempar ke view input
                if((!isset($_GET['nama']))&&(!isset($_GET['deskripsi']))&&(!isset($_GET['simpan'])))
                {
                    include 'view/input.php';
                }

                // simpan data ke database
                else{
                    $hasil=$this->dao_kat->simpan($_GET['nama'],$_GET['deskripsi']);
                    $result=$this->dao_kat->select();
                    include 'view/cetak.php';
                }            
            }
        }
        
        if(isset($_SERVER['CONTENT_TYPE'])){
            $type1=$_SERVER['CONTENT_TYPE'];

            if(strpos($type1,'application/json')!== false){

                // header content type: application/json

                // post/create ke database database
				if((!isset($_POST['nama']))&&(!isset($_POST['deskripsi']))&&(!isset($_POST['simpan'])))
                {
					header('Content-Type: application/json');
					echo json_encode($result);
                }
				
			}

            // header content type: application/x-www-form-urlencoded
			if(strpos($type1,'application/x-www-form-urlencoded')!== false){
					

                    //edit
                    if((isset($_POST['nama']))&&(isset($_POST['deskripsi']))&&(isset($_POST['id'])))
                    {
                        $hasil=$this->dao_kat->edit($_POST['nama'],$_POST['deskripsi'], $_POST['id']);
                        if($hasil==1){
                            header('Content-Type: application/json');
                            echo json_encode('status edit:berhasil');
                        }
                    }

                    //simpan
                    elseif((isset($_POST['nama']))&&(isset($_POST['deskripsi'])))
                    {
                        $hasil=$this->dao_kat->simpan($_POST['nama'],$_POST['deskripsi']);
                        if($hasil==1){
                            header('Content-Type: application/json');
                            echo json_encode('status simpan:berhasil');
                        }
                    }

                    //delete
                    elseif(isset($_POST['id']))
                    {
                        $hasil=$this->dao_kat->hapus($_POST['id']);
                        if($hasil==1){
                            header('Content-Type: application/json');
                            echo json_encode('status hapus:berhasil');
                        }
                    }
				}
				
		}
	}
}
?>
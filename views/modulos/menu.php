<?PHP
  //La variable Menu se  utiliza  de  bandera, al  remover el  nodo del ciclo, los  padres se  califican como  hojas y no pasan por la seccion de padre.
  function arbol($arbol,$padre){
    $rama=[];
    foreach($arbol as $nodo){
      if($nodo['IdPadre']==$padre){
        $hojas=arbol($arbol,$nodo['IdMenu']);
        if(count($hojas)>0){
          //Seccion Padre
          $menu='';
          foreach($hojas as $hoja){
            if(isset($hoja['menu'])){
               $menu.=$hoja['menu'];
               $nodo['menu']=  '<li class="nav-item menu-close">'.
                  '<a href="#" class="nav-link">'.
                    '<i class="'.$nodo['ICON'].'"></i>'.
                    '<p>'.$nodo['MenuName'].
                      '<i class="right fas fa-angle-left"></i>'.
                    '</p>'.
                  '</a>'.
                  '<ul class="nav nav-treeview ">'.
                    $menu.
                  '</ul>'.
                '</li>';
              $nodo['hojas']=$hojas;
            }
          }
        }
        else{
          //Seccion Hijos
          foreach($_SESSION['Permisos'] as $permisos){
            if($permisos['Modulo']==$nodo['IdModulo'])
            {
              $nodo['menu']= '<li class="nav-item">'.
                    '<a href="'.$nodo['PageName'].'" class="nav-link">'.
                      '<i class="'.$nodo['ICON'].'"></i>'.
                      '<p>'.$nodo['MenuName'].'</p>'.
                    '</a>'.
                  '</li>';
              break;
            }
          }
        }
        $rama[]=$nodo;
      }
    }
    return $rama;
  }
  ?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="views/dist/img/TCI.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><b>TCI</b></span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php
            if($_SESSION['Foto']!=""){
              echo '<img src="'.$_SESSION['Foto'].'" class="img-circle elevation-2" alt="User Image">';
            }
            else{
              echo '<img src="views/dist/img/user.png" class="img-circle elevation-2" alt="User Image"> ';
            }
          ?>
        </div>
        <div class="info">
            <!--<a href="#" class="d-block">Francisco Rodriguez</a> -->
          <?php
            if($_SESSION['Usuario']!=""){
              echo '<a href="#" class="d_block">'. $_SESSION['Usuario'].'</a>';
              echo '<input type="hidden" class="form-control input-sm" id="GNL_USER" value = "'.$_SESSION['Usuario'].'" readonly >';
            }
            else{
              echo '<a href="#" class="d-block">Nombre de  usuario</a> ';
            }
          ?>
        </div>
      </div>
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2" id="menu">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?PHP
          //VAR_DUMP($_SESSION['Permisos']);
          $Raiz=1;
          $Menu=ModeloMenu::MdlMenu();
          $arbol=arbol($Menu,$Raiz);
          foreach($arbol as $nivel){
            if(isset($nivel['menu'])){
              echo($nivel['menu']);
            }
          }
          ?>
          <li class="nav-item menu-close">
            <a href="logout" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Off
                </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

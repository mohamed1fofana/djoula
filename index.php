<?php
  include "../../../Jfwk.php";
  include "../../../model/app/gestionPersonnels.php";
  include "../../../model/app/typeArticle.php";
  include "../../../model/app/gestionFournisseur.php";
  include "../../../model/app/Service.php";
  include "../../../model/app/gestionClients.php";
  include "../../../model/app/gestionArticle.php";
  include "../../../model/app/situationClient.php";
  include "../../../model/app/gestionBoutique.php";
  include "../../../model/app/gestionEtablissement.php";
  include "../../../model/app/gestionVente.php";
  include "../../../model/app/gestionDepanses.php";


  include 'header.php';

  $user=new User();  
  $personnels=new Personnels();
  $article=new Article();   
  $client=new Clients(); 
  $situation=new SituationClient();
  $fournisseur=new Fournisseurs();
  $boutique=new Boutique();
  $etablissement=new Etablissement();
  $sousArticle=new TypeArticle();
  $vente=new Vente();
  $depense=new Depense();

  $vente=new Vente();
  $database=new Database();
  $db=$database->dbConnect();
  $totalEncaissementJour=0;
  $ganJournalier=0;

   
  $dataVente=$vente->selectAllEncaissementJours(date('Y-m-d'));
  $montantGainJouranaliere=0;
  $gain=0;
  $prixVent=0;
  for($i=0;$i<count($dataVente);$i++){
    $totalEncaissementJour+=$dataVente[$i]['prixUnitaire']*$dataVente[$i]['QtLivre'];
    $prixVent=$article->getInfoArticle($dataVente[$i]['idArticle'])['prixAchat'];

    $gain=($dataVente[$i]['prixUnitaire']-$prixVent)*$dataVente[$i]['QtVendue'];
    $montantGainJouranaliere+=$gain;
    
  }


  $montantGainAnnuelle=0;
  $gainAnnuelle=0;
  $prixVenteAnnuelle=0;
  $dataGainAnnuelle=$vente->selectAllEncaissementAnnee(date('Y'));
  for($x=0;$x<count($dataGainAnnuelle);$x++){
    $prixVenteAnnuelle=$article->getInfoArticle($dataGainAnnuelle[$x]['idArticle'])['prixAchat'];
    $gainAnnuelle=($dataGainAnnuelle[$x]['prixUnitaire']-$prixVenteAnnuelle)*$dataGainAnnuelle[$x]['QtVendue'];
    $montantGainAnnuelle+=$gainAnnuelle;
    
  }
 

   

  $totalDepense=0;
  $dataDepense=$depense->selectAllDepenseJours(date('Y-m-d'));
  for($x=0;$x<count($dataDepense);$x++){
    $totalDepense+=$dataDepense[$x]['montantDepense'];
  }

  $annuelEncaissement=0;

  $prixRevient=0;
  $dataEncaissementAnnuelle=$vente->selectAllVenteData();
  for($i=0;$i<count($dataEncaissementAnnuelle);$i++){
    $annuelEncaissement+=$dataEncaissementAnnuelle[$i]['prixUnitaire']*$dataEncaissementAnnuelle[$i]['QtVendue'];
    $prix=$article->getInfoArticle($dataEncaissementAnnuelle[$i]['idArticle'])['prixRevienRemise'];

  }

  //CALCULE DU VALEUR DE STOCK
  $valeur=0;
  $montantTotalValeur=0;
  $dataStock=$article->selectAllStocks();
  for($i=0;$i<count($dataStock);$i++){

    $prixVentStock=$article->getInfoArticle($dataStock[$i]['idArticle'])['prixVente'];
    $valeur=$prixVentStock*$dataStock[$i]['quantiteInitialProduit'];
    $montantTotalValeur+=$valeur;
 
  }


  $totalDepenseAnnuelle=0;
  $dataDepenseAnnuel=$depense->selectAllDepenseAnnuelle();
  for($x=0;$x<count($dataDepenseAnnuel);$x++){
    $totalDepenseAnnuelle+=$dataDepenseAnnuel[$x]['montantDepense'];
  }




?>
   
 
   <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row" >
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info" >
              <div class="inner">
                <h5><?php echo number_format($totalEncaissementJour).' GNF' ?></h5>

                <p>caisse du jour</p>
              </div>
              <div class="icon">
              <!--<i class="fas fa-calculator"></i>-->
              <i class="fa fa-fw fa-shopping-cart"></i>
              </div>
              <a href="#" class="small-box-footer">Plus info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
              <h5><?php echo number_format($totalDepense).' GNF' ?></h5>

                <p>Dépenses du jour</p>
              </div>
              <div class="icon">
              <i class="fas fa-calculator"></i>
              <!--<i class="fas fa-dollar-sign"></i>-->
              </div>
              <a href="#" class="small-box-footer">Plus info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
              <h5><?php echo number_format($totalEncaissementJour-$totalDepense).' GNF' ?></h5>

                <p>Solde du jour</p>
              </div>
              <div class="icon">
              <i class="fas fa-dollar-sign"></i>
              </div>
              <a href="#" class="small-box-footer">Plus info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
              <h5><?php echo number_format($annuelEncaissement-$totalDepenseAnnuelle).' GNF' ?></h5>

                <p>Solde Générale</p>
              </div>
              <div class="icon">
              <i class="fas fa-dollar-sign"></i>
              </div>
              <a href="#" class="small-box-footer">Plus info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-6 connectedSortable ui-sortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                  <i class="fas fa-chart-bar mr-1"style="font-size: 16px; color: rgb(201, 201, 200);"></i>
                  Situation Général
                </h3>
                <div class="card-tools">
                   
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0" >
                    <div class="row" style="padding:1%;">
                        <div class="col-lg-3 col-6">
                            <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <i class="fa fa-fw fa-shopping-cart"style="font-size: 16px; color: rgb(201, 201, 200);"></i> CA année
                                        <?php echo '<br>'.number_format($annuelEncaissement).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                        <i class="fas fa-dollar-sign"></i> Gain annuelle
                                        <?php echo '<br>'.number_format($montantGainAnnuelle).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <i class="fa fa-fw fa-credit-card" style="font-size: 16px; color: rgb(201, 201, 200);"></i> Depense annuelle
                                        <?php echo '<br>'.number_format($totalDepenseAnnuelle).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                        <i class="fas fa-dollar-sign"></i> Renteblité annuelle
                                        <?php echo '<br>'.number_format($montantGainAnnuelle-$totalDepense).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                             </div>
                        </div>
                    </div>
                    
                    <div class="row" style="padding:1%;">
                        <div class="col-lg-3 col-6">
                              <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <img width="16" height="16" src="https://www.flaticon.com/svg/static/icons/svg/3176/3176360.svg" alt="Line chart free icon" title="Line chart free icon" class="loaded"> Valeur de stock
                                        <?php echo '<br>'.number_format($montantTotalValeur).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <i class="fa fa-fw fa-bell" style="font-size: 16px; color: rgb(201, 201, 200);"></i> total a recouvrir
                                        <?php echo '<br>'.number_format(0).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                        <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <i class="fa fa-fw fa-cube" style="font-size: 16px; color: rgb(201, 201, 200);"></i> non livré
                                        <?php echo '<br>'.number_format(20).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                        <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <i class="fa fa-fw fa-users" style="font-size: 16px; color: rgb(201, 201, 200);"></i> total du client
                                        <?php echo '<br>'.number_format(20).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:1%;">
                        <div class="col-lg-3 col-6">
                            <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <i class="fa fa-fw fa-home" style="font-size: 16px; color: rgb(201, 201, 200);"></i> Recouvr.interne
                                        <?php echo '<br>'.number_format(20).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <i class="fa fa-fw fa-home" style="font-size: 16px; color: rgb(201, 201, 200);"></i> total du interne
                                        <?php echo '<br>'.number_format(20).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                        <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                    <i class="fa fa-fw fa-users" style="font-size: 16px; color: rgb(201, 201, 200);"></i> total fournisseur
                                        <?php echo '<br>'.number_format(0).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                        <div style="border:1px solid #d4cdcd;">
                                <div style="padding:1.5%">
                                    <p style="font-size:12px; text-align:center; font-weight:bolder;">
                                        <i class="fas fa-dollar-sign"></i> Gain jour
                                        <?php echo '<br>'.number_format($montantGainJouranaliere).' GNF'; ?>
                                    </p>
                                </div>
                                <small></small>
                            </div>
                        </div>
                    </div>
                  
                   
                </div>
              </div><!-- /.card-body -->
              
            </div>
            <!-- /.card -->

            
<style>
             table{
               border:1px solid #e4dfdf;;
               width:80%;
               padding:0.2%;
                border-collapse: collapse;
                border-spacing: 0;
                
             }
             th{
               border:1px solid white;
               text-align:center;
                color:white;
               font-family:cambria;
               font-size:13px;
             }
             td{
               font-size:11px;
               border:1px solid #e4dfdf;;
               
             }

             tr:nth-child(even){background-color: #f2f2f2}

             .img {
  border-radius: 50%;
  -webkit-transition: -webkit-transform .8s ease-in-out;
          transition:         transform .8s ease-in-out;
}
             .img:hover{
              -webkit-transform: rotate(360deg);
          transform: rotate(360deg);
             }
            </style>

 
            <!-- TO DO List -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-bar mr-1"style="font-size: 16px; color: rgb(201, 201, 200);"></i>
                  Ventes du jour par article</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 300px;">
                <table class="table table-head-fixed text-nowrap">
                  <thead>
                      <tr>
                          <th style="background-color:#28a745; ">Désignation</th>
                          <th style="background-color:#28a745;">Quantité</th>
                          <th style="background-color:#28a745;">Montant</th>
                      </tr>
                  </thead>
                  <tbody>

                  
                  <?php 
  $dataVente=$vente->selectAllVenteArticles(date('Y-m-d'));
  $tabArticle=array();
  $tabArticle[0]=0;
  $idArticle=0;
  for($i=0;$i<count($dataVente);$i++){
    $nomArticle=$article->getInfoArticle($dataVente[$i]['idArticle'])['designations'];

    if(in_array($dataVente[$i]['idArticle'],$tabArticle))  {
      $idArticle=1;
    }

    $tabArticle[]=$dataVente[$i]['idArticle'];
    
?>

<tr>
                    <?php
                      $quantitTotal=0;
                      $totalMonant=0;
                      $dataVentes=$vente->selectAllVenteArticleJours($dataVente[$i]['idArticle'],date('Y-m-d'));
                      for($j=0;$j<count($dataVentes);$j++){
                        $quantitTotal+=$dataVentes[$j]['QtVendue']; 
                        $totalMonant+=$dataVentes[$j]['QtVendue']*$dataVentes[$j]['prixUnitaire'];
                    
                      }

                    ?>
                    <?php if($idArticle==0){ ?>
                      <td><?php echo $nomArticle; ?></td>
                    <td><?php echo $quantitTotal ?></td>
                    <td><?php echo number_format($totalMonant)  ?> GNF</td>
                    <?php } ?>
                </tr>

<?php $idArticle=0; } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>


          </section>

          
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-6 connectedSortable ui-sortable">
                <!-- TO DO List -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"style="font-size: 16px; color: rgb(201, 201, 200);"></i>Situation des boutiques</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 265px;">
                <table class="table table-head-fixed text-nowrap" style="font-size:12px; border : 1px solid #e4dfdf; padding:2%; margin:1%; ">
                  <thead style="padding:2%;">
                  <tr style="border:1px solid #e4dfdf;">
                      <th style="border:1px solid #e4dfdf; background-color:#28a745; ">Boutiques</th>
                      <th style="border:1px solid #e4dfdf; background-color:#28a745;">Non livré</th>
                      <th style="border:1px solid #e4dfdf; background-color:#28a745; ">Marges</th>
                      <th style="border:1px solid #e4dfdf; background-color:#28a745; ">Charges</th>
                      <th style="border:1px solid #e4dfdf; background-color:#28a745; ">Rentabilité</th>
                    </tr>
                  </thead>
                  <tbody>

                  
                    
                    
<?php 

  // $dataVente=$vente->selectAllVenteData();
  // $qtG=0;
  // for($i=0;$i<count($dataVente);$i++){
  //   $totalEncaissementJour+=$dataVente[$i]['prixUnitaire']*$dataVente[$i]['QtLivre'];
  //   $qtG+=$dataVente[$i]['QtLivre'];

  $dataBoutique=$boutique->selectAllBoutiuqe();
  $prixRevientss=0;
  $prixRevients=0;
  $quantiteVendue=0;


  for($i=0;$i<count($dataBoutique);$i++){

?>
                    <tr style="font-family:'Times New Roman', Times, serif; font-weight:bold;">
                      <td style="border:1px solid #e4dfdf;"><?php echo $dataBoutique[$i]['nomBoutique']; ?></td>

                      <?php 
                        $montantBoutique=0;
                        $montantBoutiqueNomLivre=0;
                        $quantiteNonLivre=0;
                        $charge=0;
                        $totalDepense=0;
                        $marche=0;
                        $benefice=0;
                        $prixVents=0;
                        $dataVente=$vente->selectAllVenteBoutique($dataBoutique[$i]['idBoutique'],date('Y'));
                        for($j=0;$j<count($dataVente);$j++){
                          $quantiteNonLivre=$dataVente[$j]['QtVendue']-$dataVente[$j]['QtLivre'];
                          $quantiteVendue+=$dataVente[$j]['QtVendue'];
                          $montantBoutique+=$dataVente[$j]['prixUnitaire']*$dataVente[$j]['QtVendue'];
                          $montantBoutiqueNomLivre+=$dataVente[$j]['prixUnitaire']*$quantiteNonLivre;
                          $montantBoutique-$totalDepense;
                          $prixRevients=$article->getInfoArticle($dataVente[$i]['idArticle'])['prixAchat'];
                          $benefice=$dataVente[$j]['prixUnitaire'];
                          $prixVents+=$article->getInfoArticle($dataVente[$j]['idArticle'])['prixAchat'];
                          $prixRevients+=(($dataVente[$j]['prixUnitaire']-$prixVents));


                      
                        }
                        $totalDepense=0;
                        $dataDepense=$depense->selectAllDepense($dataBoutique[$i]['idBoutique'],date('Y'));
                        for($x=0;$x<count($dataDepense);$x++){
                          $totalDepense+=$dataDepense[$x]['montantDepense'];
                        }
                        $marche=$montantBoutique-$totalDepense;

                        $prixRevientss=$prixRevients*$quantiteVendue;


                      ?>

                        <td style="border:1px solid #e4dfdf; text-align:right"><?php echo number_format($montantBoutiqueNomLivre); ?> GNF</td>
                        <?php 
                          if($marche<0){
                        ?>
                          <td style="border:1px solid #e4dfdf; text-align:right; background-color:darkred; color:whitesmoke"><?php echo number_format($montantGainAnnuelle); ?> GNF</td>
                        <?php 
                          }else{
                        ?>
                          <td style="border:1px solid #e4dfdf; text-align:right"><?php echo number_format($montantGainAnnuelle); ?> GNF</td>
                          <?php } ?>

                        <td style="border:1px solid #e4dfdf; text-align:right"><?php echo number_format($totalDepense); ?> GNF</td>
                        <?php if(($montantGainAnnuelle-$totalDepense)<0){ ?>
                          <td style="border:1px solid #e4dfdf; text-align:right; background-color:darkred; color:white;"><?php echo number_format($montantGainAnnuelle-$totalDepense); ?> GNF</td>
                        <?php }else{ ?>
                          <td style="border:1px solid #e4dfdf; text-align:right;"><?php echo number_format($montantGainAnnuelle-$totalDepense); ?> GNF</td>
                        <?php } ?>

                        

                      
                    </tr>
<?php 
  }
?>   
                    
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>  
            <!-- dix meilleur vente -->
            <div class="card-body table-responsive p-0" style="height: 360px;">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Top vente article du jour</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 417px;" width="834" height="500" class="chartjs-render-monitor"></canvas>
              </div>
              </div>
              <!-- /.card-body -->
            </div>

          </section>
          <!-- right col -->

        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    
    <!-- graphe annuelle -->
    
    <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Statistiques des ventes par année</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <div class="chartjs-size-monitor">
                    <div class="chartjs-size-monitor-expand">
                      <div class="">
                      </div>
                    </div>
                    <div class="chartjs-size-monitor-shrink">
                      <div class="">
                      </div>
                    </div>
                  </div>
                  <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 417px;" width="834" height="500" class="chartjs-render-monitor"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
     </div>

 

<?php
  include 'footer.php';
?>

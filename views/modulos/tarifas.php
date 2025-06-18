<?php

include_once 'views/modulos/functions/miaccount.functions.php';


$active = "Tarifas y Prefijos";

?>

<main id="main" class="main">

        <?php include_once 'views/modulos/includes/ruta.php'; ?>
        <!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                          <br>

                            <!-- Table with stripped rows -->
                            <table id="table" class="table table-bordered table-striped">
                            <br>
                            <thead>
                              <tr>
                                <th>Nombre del Area</th>
                                <th>Prefijo</th>
                                <th>Tarifa</th>
                                <th>Observacion</th>
                              </tr>
                            </thead>
                                <tbody>
                                <?php 

                                    foreach ($rateminutearray as $key => $value){
                                        echo '<tr>
                                        <td>'.$value["areaname"].'</td>
                                        <td>'.$value["prefix"]."</td>
                                        <td>$ ".$value["rate(minute)"]."</td>
                                        <td>".$value["memo"].'</td>
                                        </tr>';
                                    }

                                ?>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
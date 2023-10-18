@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Management Data Master Tarif Delivery</p>

</div>
<div class="page-content">
  <?php
  $menuArr = [
    "LOLO FULL KAPAL SANDAR ICON (2 Invoice)",
    "LOLO FULL KAPAL SANDAR ICON(1 Invoice)",
    "LOLO MT (1 INVOICE)",
    "JPB EX-TRUCK/ STUFFING MUATAN KAPAL ICON",
    "JPB EX-TRUCK/ STUFFING MUATAN KAPAL LUAR ",
    "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ICL) 1 INVOICE",
    "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ERNA) 2 INVOICE ",
    "HANDLING CHARGE ERNA VIA KAPAL LUAR (INVOICE ERNA) 2 INVOICE",
    "MUAT DRY SP2",
    "MUAT DRY SPPS",
  ];
  $orderServiceArr = [
    "lolofull",
    "lolofull1inv",
    "lolomt",
    "jpbicon",
    "jpbluar",
    "ernahandling1inv",
    "ernahandling2inv",
    "ernahandlingluar",
    "sp2dry",
    "sppsdry",
  ];

  ?>

  <?php
  $i = 1;
  $k = 0;
  foreach ($menuArr as $menu) { ?>
    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">
              Tabel Data Master Tarif Receiving <?= $menu ?>
            </h4>
            <a href="/receiving/mastertarif/create?orderservice=<?= $orderServiceArr[$k] ?>" class="btn btn-success text-white mb-4" type="button"><i class="fa fa-plus"></i> Tambah</a>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table<?= $i ?>">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Type</th>
                      <th>Size</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($mastertarif as $value) { ?>
                      <?php if ($value->orderservice == $orderServiceArr[$k]) { ?>
                        <tr>
                          <td><?= $value->id ?></td>
                          <td><?= $value->type ?></td>
                          <td><?= $value->size ?></td>
                          <td><?= $value->status ?></td>
                          <td><a href="/receiving/mastertarif/detail?id=<?= $value->id ?>" class="btn btn-success" type="button"><i class="fa fa-eye"></i></a></td>
                        </tr>
                      <?php } ?>
                    <?php
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php
    $i++;
    $k++;
  } ?>
</div>


@endsection
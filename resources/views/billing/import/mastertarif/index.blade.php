@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Management Data Master Tarif Delivery</p>

</div>
<div class="page-content">
  <?php
  $menuArr = [
    "SP2 Kapal Sandar icon (MT Balik IKS)",
    "SP2 Kapal Sandar icon (MKB)",
    "SP2 Kapal Sandar icon (MT Balik Pelindo)",
    "SPPS",
    "SPPS (Relokasi Pelindo - ICON)",
    "SP2 (Relokasi Pelindo - ICON)",
  ];
  $orderServiceArr = [
    "sp2iks",
    "sp2mkb",
    "sp2pelindo",
    "spps",
    "sppsrelokasipelindo",
    "sp2icon",
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
              Tabel Data Master Tarif Delivery <?= $menu ?>
            </h4>
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
                          <td><a href="/delivery/mastertarif/detail?id=<?= $value->id ?>" class="btn btn-success" type="button"><i class="fa fa-eye"></i></a></td>
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
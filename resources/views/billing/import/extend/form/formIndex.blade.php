@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Menu Untuk Extend Delivery Form</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 mb-3">
      <a href="/delivery/form/extend/create" type="button" class="btn btn-success">
        <i class="fa-solid fa-plus"></i>
        Tambah Extend Delivery Form
      </a>
    </div>

  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Extend Delivery Form Data Table</h4>
          <p>Tabel Form Extend Delivery</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Do Number</th>
                    <th>Order Service</th>
                    <th>Expired Date</th>
                    <th>Bill Of Loading Number</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($deliveries as $data) { ?>
                    <?php if ($data->isExtended == "1") { ?>

                      <tr>
                        <td><?= $i ?></td>
                        <td><?= $data->customer->customer_name ?></td>
                        <td><?= $data->do_number ?></td>
                        <td><?= $data->orderService ?></td>
                        <td><?= DateFormat($data->exp_date) ?></td>
                        <td><?= $data->boln ?></td>
                        <td><?= DateTimeFormat($data->createdAt) ?></td>
                        <td>
                          <?php if ($data->hasInvoice != null) { ?>
                            <span class="badge bg-success text-white">Has Invoice</span>
                          <?php } else { ?>
                            <span class="badge bg-warning text-white">Draft</span>
                          <?php } ?>
                        </td>
                        <td>
                          <?php if ($data->hasInvoice != null) { ?>
                            <button disabled type="button" class="btn btn-sm btn-success"><i class="fa fa-file"></i></button>
                          <?php } else { ?>
                            <button class="btn btn-info text-white" data-toggle="tooltip" data-placement="top" title="Still On Development">
                              <!-- <a href="/invoice/add/update_step1?id=<?= $data->id ?>"><i class="fa fa-pencil"></i></a> -->
                              <a><i class="fa fa-pencil"></i></a>
                            </button>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php } ?>

                  <?php
                    $i++;
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@include('invoice.modal.modal')


@endsection
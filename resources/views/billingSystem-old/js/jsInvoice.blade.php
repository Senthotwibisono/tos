<script>
    async function changeCustomer(customerId) {
        // console.log(customerId);
        const url = "{{route('invoiceService.getData.customer')}}";
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({ customerId: customerId }),
        });

        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                return hasil.data;
            } else {
                errorHasil(hasil);
                return false;
            }
        } else {
            errorResponse(response);
            return false;
        }
    }
    
    async function getDoData(data) {
        // console.log(data);
        const url = '{{route('invoiceService.getData.doData')}}';
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data),
        })
       return response;
    }
    async function createForm(data) {
        // console.log(data);
        const url = '{{route('invoiceService.import.postForm')}}';
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data),
        })
       return response;
    }

    async function searchToPay(event) {
        showLoading();
        const id = event.getAttribute('data-id');
        const type = event.getAttribute('data-type');
        const data = {
            id:id,
            type:type,
        };

        const url = "{{route('invoiceService.getData.searchToPay')}}";
        const response = await fetch(url,{
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data),
        });
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                console.log(hasil);
                $('#payModal').modal('show');
                $('#payModal #proforma_no_edit').val(hasil.data.proforma_no);
                $('#payModal #type_edit').val(hasil.type);
                $('#payModal #id_edit').val(hasil.data.id);
                $('#payModal #inv_type_edit').val(hasil.data.inv_type);
                $('#payModal #grand_total_edit').val(hasil.data.grand_total);
                if (hasil.another) {
                    $('#payModal #anotherForm').removeClass('d-none');
                    $('#payModal #anotherProforma').val(hasil.anotherData.proforma_no);
                    $('#payModal #anotherType').val(hasil.anotherData.inv_type);
                    $('#payModal #anotherGrandTotal').val(hasil.anotherData.grand_total);
                } else {
                    $('#payModal #anotherForm').addClass('d-none');
                    $('#payModal #couple').prop('checked', false);
                }
            }else{
                errorHasil(hasil);
                return;
            }
        }else{
            errorResponse(response);
            return;
        }
    }

    async function dataToPay() {
        const id = document.getElementById('id_edit').value;
        const type = document.getElementById('type_edit').value;
        const coupleCheckbox = document.getElementById('couple');
        const isCoupleChecked = coupleCheckbox.checked;
        const couple = isCoupleChecked ? 'Y' : 'N';
        const data = {
            id:id,
            type:type,
            couple:couple,
        };

        return data;
    }
    
    async function lunas() {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure',
            text: 'pastikan pilihan anda sudah benar',
            showCancelButton: true
        }).then( async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const data = await dataToPay();
                const action = 'lunas';
                const payload = {
                    data,
                    action: action
                };
                paymentProccess(payload);
            }else{
                return;
            }
        })
    }

    async function piutang() {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure',
            text: 'pastikan pilihan anda sudah benar',
            showCancelButton: true
        }).then( async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const data = await dataToPay();
                const action = 'piutang';
                const payload = {
                    data,
                    action: action
                };
                paymentProccess(payload);
            }else{
                return;
            }
        })
    }

    async function paymentProccess(payload) {
        const url = "{{route('invoiceService.pay.manualPayment')}}";
        const response = await fetch(url,{
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(payload),
        });
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            console.log(hasil);
            if (hasil.success) {
                successHasil(hasil);
            }else{
                errorHasil(hasil);
            }
        }else{
            errorResponse(response);
        }
    }

    async function cancelInvoice(event) {
        Swal.fire({
            icon:'warning',
            title: 'Apakah anda yakin?',
            text: 'Jika invoice dicancel, butuh waktu dalam pemulihan invoice',
            showCancelButton: true,
        }).then(async(result) =>{
            if (result.isConfirmed) {
                showLoading();
                const id = event.getAttribute('data-id');
                const type = event.getAttribute('data-type');
                const data = {
                    id : id,
                    type : type,
                };
                console.log(data);
                const url = "{{ route('invoiceService.cancel.CancelInvoice')}}";
                const response = await fetch(url, {
                    method : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify(data),
                });
                hideLoading();
                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        successHasil(hasil);
                    }else{
                        errorHasil(hasil);
                    }
                }else{
                    errorResponse(response);
                }
            }else{
                return;
            }
        });
    }

    //  Virtual Account
    async function createVA() {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            text: 'Apakah anda yakin dengan metode pembayaran ini?',
            showCancelButton: true
        }).then(async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const data = await dataToPay();
                const action = 'viertualAccount';
                const payload = {
                    data,
                    action: action
                };
                const url = "{{route('invoiceService.va.create')}}";
                const response = await fetch(url,{
                    method : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify(payload),
                });
                hideLoading();
                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                        }).then(() => {
                            window.open('/pembayaran/virtual_account-' + hasil.data.id, '_blank', 'width=800,height=600');
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: hasil.message,
                        }).then(() => {
                            if (hasil.status === 30) {
                                window.open('/pembayaran/virtual_account-' + hasil.data.id, '_blank', 'width=800,height=600');
                            } else {
                                window.location.reload();
                            }
                        });
                    }
                }else{
                    errorResponse(response);
                }
            }else{
                return;
            }
        });
    }

    async function getInvoiceData(data) {
        const url = "{{route('invoiceService.getData.OldInvoiceData')}}";
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data),
        });

        return response;
    }

    async function submitFormExtend(data){
        const url = "{{route('invoiceService.extend.formPost')}}";
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data),
        });

        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!!',
                    text: hasil.message,
                }).then(() =>{
                    const id = hasil.data;
                    const url = '{{ route("extend.form.preinvoice", ["id" => ":id"]) }}'.replace(':id', id);
                    window.location.href = url;
                })
            }else{
                errorHasil(hasil);
            }
        }else{
            errorResponse(response);
        }
    }
</script>
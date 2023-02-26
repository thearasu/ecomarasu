$(document).ready(function(){
    authUser()
    createProductElements()
    showAddProductsForm()
})

function authUser(){
    $.ajax({
        type: "GET",
        url: "api.php",
        data: {
            action: "check-logged-in"
        },
        success: function (response) {
            console.log(response)
            if(response != 1){
                window.location.href = '../'
            }
        }
    });
}

function createProductElements(){
    $.ajax({
        type: "GET",
        url: "api.php",
        data: {
            action: "get-all-products"
        },
        success: function(response){
            const products = JSON.parse(response)
            for (const product of products){
                productElement = `
                <div class="Box d-flex flex-self-center col-12 m-1 p-1 droppable">
                    <div class="d-flex flex-items-center draggable" data-id="${product.id}">
                        <div class="col-2 d-flex flex-items-center flex-items-center flex-justify-center">
                            <img class="col-8 p-2" src="../public/images/${product.file_name}" alt="github" />
                        </div>
                        <div class="col-10">
                            <h1 class="text-normal lh-condensed"></h1>
                            <p class="h4 color-fg-muted text-normal mb-2">${product.name}</p>
                            <a class="color-fg-muted text-small" href="#url">$${product.price}</a>
                            <button class="btn btn-sm mr-2 float-right btn-primary edit" type="button">Edit</button>
                            <button class="btn btn-sm mr-2 float-right btn-danger delete" type="button">Delete</button>
                        </div>
                    </div>
                </div>
                `
                $('#products-container').append(productElement)
            }
            addDragAndDrop();
            $('.delete').click(function(e) { 
                e.preventDefault()
                id = $(this).closest('.draggable').attr('data-id')
                $.ajax({
                    type: "POST",
                    url: "api.php",
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function (response) {
                        if(response == 1){
                            refreshProducts()
                        }
                    }
                });
            });
            $('.edit').click(function (e) { 
                e.preventDefault()
                id = $(this).closest('.draggable').attr('data-id')
                createEditForm(id)
            });
        }
    })
}

function addDragAndDrop(){
    let dragStartId;
    $('.draggable').draggable({
         start: function() {
            dragStartId = $(this).attr('data-id')
          }
    })
    $('.droppable').droppable({
        accept: '.draggable',
        over: function(){
            $(this).addClass('color-bg-accent')
        },
        out: function(){
            $(this).removeClass('color-bg-accent')
        },
        drop: function(){
            const dragDropId = $(this).find('.draggable').attr('data-id')
            swapProducts(dragStartId,dragDropId)
        }
    })
}

function swapProducts($fromId, $toId){
    $.ajax({
        type: "POST",
        url: "api.php",
        data: {
            action: "swap",
            fromId: $fromId,
            toId: $toId,
        },
        success: function(){
            refreshProducts()
            showToast('success','Sord order changed successfully!')
        }
    })
}

function refreshProducts(){
    $('#products-container').empty()
    createProductElements()
}

function showToast(status, message){
    $.toast({
        text: message,
        loader: false,
        icon: status,
        position: 'top-center'
    })
}

function showAddProductsForm(){
    $('#add-product-button').click(function(e) { 
        e.preventDefault();
        $('#product-container').addClass('d-none')
        $('#add-product-form').removeClass('d-none')
        $('#add-product-cancel').click(function (e) { 
            e.preventDefault();
            $('#product-container').removeClass('d-none')
            $('#add-product-form').addClass('d-none')
            $('#product-form')[0].reset();
        });
        $.ajax({
            type: "GET",
            url: "api.php",
            data: {
                action: "get-default-sort"
            },
            success: function (defaultSort) {
                $('#sort_order').val(defaultSort)
                $('#sort_order').blur(function() {
                    sort_order = $(this).val()
                    if(sort_order!=''){
                        $.ajax({
                            type: "GET",
                            url: "api.php",
                            data: {
                                action: 'check-sort-exists',
                                sort_order: sort_order
                            },
                            success: function (isOrderExists) {
                                if(isOrderExists==1){
                                    $('#sort_order_warning').text(`A product with priority ${sort_order} already exists. It will change the existing product's priority to ${defaultSort}.`)
                                    $('#sort-order-formgroup').addClass("successed mb-6")
                                }else{
                                    $('#sort-order-formgroup').removeClass("successed mb-6")
                                }
                            }
                        });
                    }
                });
            }
        });
        $('#product-form').submit(function (e) { 
            e.preventDefault()
            formData = new FormData($(this)[0])
            $.ajax({
                type: "POST",
                url: "api.php",
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    result = JSON.parse(response)
                    if(result.success == 1){
                        showToast('success','Product created successfully!')
                        $('#product-container').removeClass('d-none')
                        $('#add-product-form').addClass('d-none')
                        $('#product-form')[0].reset()
                        refreshProducts()
                    }else{
                        showToast('error',result.error)
                    }
                }
            });
        });
    });
}

function createEditForm(product_id){
    $('#product-container').addClass('d-none')
    $('#edit-product-form').removeClass('d-none')
    $.ajax({
        type: "GET",
        url: "api.php",
        data: {
            action: "get-product",
            id: product_id
        },
        success: function (response) {
            product = JSON.parse(response)
            editForm = `
            <form class="Box col-10 col-md-8 col-lg-6 mr-2 pb-4 ml-2 rounded-0 d-flex  flex-column flex-align-center flex-justify-center" method="POST" id="edit-product" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="${product.id}">
                <div class="form-group flex-self-center col-10">
                    <div class="form-group-header"><label for="name">Name</label></div>
                    <div class="form-group-body"><input type="text" class="form-control width-full" name="name" value="${product.name}" id="name" placeholder="eg: Product A" required></div>
                </div>
                <div class="form-group flex-self-center col-10">
                    <div class="form-group-header"><label for="price">Price</label></div>
                    <div class="form-group-body"><input type="text" class="form-control width-full" name="price" value="${product.price}" id="price" placeholder="eg: 788" required></div>
                </div>
                <div class="form-group flex-self-center col-10">
                    <div class="form-group-header"><label for="file">Image</label></div>
                    <div class="form-group-body"><input type="file" class="width-full" name="file" id="file"></div>
                </div>
                <div class="form-group flex-self-center col-10">
                    <div class="form-group-header"><label for="price">Description</label></div>
                    <div class="form-group-body"><textarea type="text" class="form-control width-full" name="description" id="description" required> ${product.description}</textarea></div>
                </div>
                <div class="flex-self-center col-10">
                    <button class="btn" id="edit-product-cancel">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="edit-product-submit">save changes</button>
                </div>
            </form>
            `
            $('#edit-product-form').append(editForm)
            $('#edit-product-cancel').click(function (e) { 
                e.preventDefault();
                $('#product-container').removeClass('d-none')
                $('#edit-product-form').addClass('d-none').empty()
                $('#product-form')[0].reset()
                refreshProducts()
            });
            $('#edit-product').submit(function (e) { 
                e.preventDefault();
                formData = new FormData($(this)[0])
                $.ajax({
                    type: "POST",
                    url: "api.php",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        result = JSON.parse(response)
                        if(result.success == true){
                            showToast('success','Product edited successfully!')
                            $('#product-container').removeClass('d-none')
                            $('#edit-product-form').addClass('d-none').empty()
                            $('#product-form')[0].reset()
                            refreshProducts()
                        }else{
                            showToast('error',result.error)
                        }
                    }
                });
            });
        }
    });
}

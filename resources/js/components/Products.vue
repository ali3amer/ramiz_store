<template>
    <div>

        <div class="row">
            <div class="col-8">
                <div v-if="'read_products' in allPermissions" class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="row">
                                            <div class="col-5"><h3 class="card-title">{{ title }}</h3></div>
                                            <div class="col-7"><input type="text" autofocus placeholder="بحث ...."
                                                                      class="form-control"
                                                                      v-model="searchProduct" @keyup="searchResults">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4"><select id="category" class="form-control"
                                                               @change="searchResults">
                                        <option value="">إختر القسم ........</option>
                                        <option v-for="(category, index) in categories" :key="category.id"
                                                :value="category.id">{{ category.name }}
                                        </option>
                                    </select></div>
                                </div>


                            </div>
                            <div class="col-2">
                                <div class="card-tools text-right">
                                    <button v-if="'create_products' in allPermissions"
                                            :disabled="categories.length <= 0" class="btn btn-primary"
                                            data-toggle="modal" @click="newModal()"
                                            :data-target="'#' + modalTitle"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr class="text-center">
                                <th>الرقم</th>
                                <th>الاسم</th>
                                <th>سعر البيع</th>
                                <th>سعر الجرد</th>
                                <th>الكميه</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in rows" :key="row.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ row.name }}</td>
                                <td>{{ row.sale_price }}</td>
                                <td>{{ row.purchase_price }}</td>
                                <td>{{ row.stock }}</td>
                                <td>

                                    <!--                                    <a href="#" data-target="#add_detail" @click="newDetail(row)"><i-->
                                    <!--                                        class="fa fa-plus blue"></i></a> /-->
                                    <a href="#" @click="loadDetail(row)"><i
                                        class="fa fa-eye blue"></i></a> /

                                    <a href="#" @click="loadPrice(row)"><i
                                        class="fa fa-money blue"></i></a> /

                                    <a v-if="'update_products' in allPermissions" href="#"
                                       :data-target="'#' + modalTitle" @click="editModal(row)"><i
                                        class="fa fa-edit blue"></i></a> / <a v-if="'delete_products' in allPermissions"
                                                                              href="#" @click="deleteData(row.id)"><i
                                    class="fa fa-trash red"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>

            <div class="col-4">


                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6"><h3 class="card-title">{{ product_name }}</h3></div>
                            <div class="col-4">
                                <input type="text" v-model="supplier.name"
                                       id="supplier" :data-supplier="data_supplier"
                                       class="form-control" readonly
                                       placeholder="اسم المورد" data-toggle="modal" data-target=".supplierSearch">

                            </div>
                            <div class="col-2">
                                <div class="card-tools text-right">
                                    <button class="btn btn-primary" data-toggle="modal"
                                            :disabled="Object.keys(supplier).length == 0"
                                            @click="newDetail({'id': product_id, 'name': product_name})"
                                            :data-target="'#add_detail'"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الباتش</th>
                                <th>الكميه</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(detail, index) in details" :key="detail.id">
                                <td>{{ detail.batch }}</td>
                                <td>{{ detail.stock }}</td>
                                <td>
                                    <a href="#" data-target="#add_detail" @click="editDetails(detail)"><i
                                        class="fa fa-edit blue"></i></a> / <a href="#"
                                                                              @click="deleteDetail(detail.id)"><i
                                    class="fa fa-trash text-danger"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                    </div>
                </div>
                <!-- /.card -->

                <div class="modal fade" id="add_detail" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">

                        <div class="modal-content">
                            <form @submit.prevent="editDetail ? updateDetail() : createDetail()">
                                <input type="hidden" name="user_id" v-model="detail_form.user_id">
                                <input type="hidden" name="product_id" v-model="detail_form.product_id">
                                <div class="modal-header">
                                    <h4 class="modal-title" style="display:inline-block" v-show="editDetail"
                                        id="addNewLabel4">
                                        تعديل بيانات</h4>
                                    <h4 class="modal-title" style="display:inline-block" v-show="!editDetail"
                                        id="addNewLabel3">
                                        إضافة كميه جديده</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" :value="product_name" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" v-model="detail_form.batch" placeholder="الباتش"
                                                       class="form-control" name="batch">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.mfgDate" type="date" name="mfgDate"
                                                       placeholder="تاريخ الانتاج"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('mfgDate') }">
                                                <has-error :form="detail_form" field="mfgDate"></has-error>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.expDate" type="date"
                                                       name="expDate" placeholder="تاريخ الانتهاء"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('expDate') }">
                                                <has-error :form="detail_form" field="expDate"></has-error>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" v-if="!editDetail">

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.purchase_price" type="number"
                                                       name="purchase_price" @change="calcAmount"
                                                       placeholder="سعر الشراء من المورد"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('purchase_price') }">
                                                <has-error :form="detail_form" field="purchase_price"></has-error>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.stock" min="1" @change="calcAmount"
                                                       type="number" name="stock"
                                                       placeholder="الكميه"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('stock') }">
                                                <has-error :form="detail_form" field="stock"></has-error>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.paid_price" min="1" @change="calcAmount"
                                                       type="number" name="paid_price"
                                                       placeholder="المدفوع"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('paid_price') }">
                                                <has-error :form="detail_form" field="stock"></has-error>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="amount" readonly type="text"
                                                       placeholder="الجمله"
                                                       class="form-control text-center">
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="remaining" @change="calcAmount" readonly type="text"
                                                       placeholder="المتبقي"
                                                       class="form-control text-center">
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" v-show="editDetail" class="btn btn-success">تعديل</button>
                                    <button type="submit" v-show="!editDetail" class="btn btn-primary">إضافه</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- /.card -->

        <div class="modal fade" :id="modalTitle" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateData() : createData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" v-show="editMode" id="addNewLabel1">
                                تعديل بيانات {{ subtitle }}</h4>
                            <h4 class="modal-title" style="display:inline-block" v-show="!editMode" id="addNewLabel">
                                إضافة {{ subtitle }} جديد</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input v-model="form.barcode" type="text" name="barcode" placeholder="باركود"
                                               class="form-control" id="barcode"
                                               :class="{ 'is-invalid': form.errors.has('barcode') }">
                                        <has-error :form="form" field="barcode"></has-error>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <select class="form-control" v-model="form.category_id" name="category_id"
                                                :class="{ 'is-invalid': form.errors.has('category_id') }">
                                            <option value="">إختر القسم ........</option>
                                            <option v-for="(category, index) in categories" :key="category.id"
                                                    :value="category.id">{{ category.name }}
                                            </option>
                                        </select>
                                        <has-error :form="form" field="category_id"></has-error>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="إسم المنتج"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <label>سعر الشراء</label>
                                    <input v-model="form.purchase_price" type="text"
                                           name="purchase_price" placeholder="سعر الشراء"
                                           class="form-control"
                                           :class="{ 'is-invalid': form.errors.has('purchase_price') }">
                                    <has-error :form="form" field="purchase_price"></has-error>
                                </div>

                                <div class="form-group col-6">
                                    <label>سعر البيع</label>
                                    <input v-model="form.sale_price" type="text" name="sale_price"
                                           placeholder="سعر البيع"
                                           class="form-control"
                                           :class="{ 'is-invalid': form.errors.has('sale_price') }">
                                    <has-error :form="form" field="sale_price"></has-error>
                                </div>

                                <div v-show="editMode" class="form-group col-6">
                                    <label><span>الكميه المتبقيه: </span>{{ form.stock }}</label></div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="submit" v-show="editMode" class="btn btn-success">تعديل</button>
                            <button type="submit" v-show="!editMode" class="btn btn-primary">إضافه</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>


        <div class="modal fade" id="showPrice" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateData() : createData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" id="addNewLabel2">
                                {{ product_name }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr class="text-center">
                                    <th>الرقم</th>
                                    <th>السعر القديم</th>
                                    <th>السعر الجديد</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody v-if="Object.keys(prices).length != 0">
                                <tr v-for="(price, index) in prices" :key="price.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ price.old_price }}</td>
                                    <td>{{ price.new_price }}</td>
                                    <td>{{ price.created_at }}</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="modal-footer">
                        </div>
                    </form>
                </div>

            </div>
        </div>


        <div class="modal fade supplierSearch" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <input type="text" v-model="supplierSearch" name="supplierSearch" id="searchSupplier"
                               @keyup="searchSupplier()" class="form-control"
                               placeholder="الإسم">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">إسم العميل</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(supplier, index) in suppliers" @click="putSupplierId(supplier)">
                                <td>{{ supplier.name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            editMode: false,
            editDetail: false,
            modalTitle: 'products',
            routeName: 'product',
            title: 'المنتجات',
            subtitle: 'منتج',
            allPermissions: {},
            rows: {},
            prices: {},
            details: {},
            detail: {},
            categories: {},
            suppliers: {},
            supplier: {},
            product_name: '',
            product_id: '',
            supplierName: '',
            data_supplier: '',
            searchProduct: '',
            supplierSearch: '',
            amount: 0,
            remaining: 0,
            detail_title: this.product_name,
            form: new Form({
                id: '',
                barcode: '',
                name: '',
                category_id: '',
                purchase_price: '',
                sale_price: '',
                stock: ''
            }),
            detail_form: new Form({
                id: '',
                supplier_id: '',
                product_id: this.product_id,
                batch: '',
                purchase_price: '',
                paid_price: '',
                stock: '',
                mfgDate: '',
                expDate: '',
                user_id: this.auth_id
            })
        }
    },
    props: ['auth_id', 'permissions'],
    methods: {
        searchResults() {
            if (this.searchProduct != '' || $('#category').val() != null) {
                axios.get('api/' + this.routeName + '?name=' + this.searchProduct + '&category=' + $('#category').val()).then(({data}) => (this.rows = data));
            } else {
                axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
            }
        },
        calcAmount() {
            this.amount = this.detail_form.stock * this.detail_form.purchase_price;
            this.remaining = this.amount - this.detail_form.paid_price;
        },
        searchSupplier() {
            if (this.supplierSearch != '') {
                axios.get('api/supplier?name=' + this.supplierSearch).then(({data}) => (this.suppliers = data));
            } else if (this.categorySearch == '') {
                axios.get('api/supplier').then(({data}) => (this.suppliers = data));
            }
        },
        putSupplierId(supplier) {
            $(".supplierSearch").modal("hide");
            this.supplier = supplier;
            this.detail_form.supplier_id = supplier.id;

        },
        getDetailResults(page = 1) {
            axios.get('api/product/' + this.product_id + '?page=' + page)
                .then(response => {
                    this.details = response.data;
                });
        },
        newDetail(row) {
            this.editDetail = false;
            this.detail_form.reset();
            this.detail_form.supplier_id = this.supplier.id;
            this.detail_form.product_id = row.id;
            this.product_name = row.name;
            $("#add_detail").modal('show');
        },
        createDetail() {
            this.$Progress.start();
            $("#add_detail").modal('hide');
            this.detail_form
                .post("api/product_Detail")
                .then(() => {
                    $("#add_detail").modal("hide");
                    toast.fire({
                        icon: "success",
                        title: "تم الحفظ بنجاح"
                    });
                    this.loadDetail({'id': this.detail_form.product_id, 'name': this.product_name});
                    this.loadData();

                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ"
                    });
                });
        },
        editDetails(detail) {
            this.editDetail = true;
            this.detail_form.reset();
            this.detail_form.supplier_id = this.supplier.id;
            $("#add_detail").modal('show');
            this.detail_form.fill(detail);
        },
        updateDetail() {
            this.$Progress.start();
            this.detail_form.put('api/product_Detail/' + this.detail_form.id).then(() => {
                // Fire.$emit('afterCreate');

                $("#add_detail").modal('hide');
                this.loadDetail({'id': this.detail_form.product_id, 'name': this.product_name});
                this.loadData();
                toast.fire({
                    icon: "success",
                    title: "تم الحفظ بنجاح"
                });

                this.$Progress.finish();
            })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ"
                    });
                });
        },
        deleteDetail(id) {
            swal.fire({
                title: 'هل أنت متأكد أنك تريد الحذف',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'لا',
                confirmButtonText: 'نعم'
            }).then((result) => {
                this.$Progress.start();
                if (result.value) {
                    this.detail_form.delete('api/product_Detail/' + id).then(() => {
                        this.loadDetail({'id': this.product_id, 'name': this.product_name});
                        this.loadData();
                        this.$Progress.finish();
                        toast.fire({
                            icon: "success",
                            title: "تم الحذف بنجاح"
                        });
                        // Fire.$emit('afterCreate');
                    }).catch(() => {
                        toast.fire({
                            icon: "error",
                            title: "لم الحذف الحفظ"
                        });
                    });

                }
            });
        },
        loadDetail(row) {
            this.detail = row;
            this.product_name = row.name;
            this.product_id = row.id;
            // this.detail_form.user_id = this.auth_id;
            axios.get('api/product/' + row.id).then(({data}) => (this.details = data));
        },
        getResults(page = 1) {
            axios.get('api/' + this.routeName + '?page=' + page)
                .then(response => {
                    this.rows = response.data;
                });
        },
        getPriceResults(page = 1) {
            axios.get('api/price?product_id=' + this.product_id + '&page=' + page)
                .then(response => {
                    this.prices = response.data;
                });
        },
        updateData() {
            this.$Progress.start();
            this.form.put('api/' + this.routeName + '/' + this.form.id).then(() => {
                // Fire.$emit('afterCreate');

                $("#" + this.modalTitle).modal('hide');
                this.loadData();
                toast.fire({
                    icon: 'success',
                    title: 'تم التعديل بنجاح'
                });

                this.$Progress.finish();
            })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم التعديل"
                    });
                });
        },
        newModal() {
            this.editMode = false;
            this.form.reset();
            $("#barcode").attr("autofocus", "autofocus");
            // $("#" + this.modalTitle).modal('show');
        },
        editModal(row) {
            this.editMode = true;
            this.form.reset();
            $("#" + this.modalTitle).modal('show');
            this.form.fill(row);
        },
        deleteData(id) {
            swal.fire({
                title: 'هل أنت متأكد أنك تريد الحذف',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'لا',
                confirmButtonText: 'نعم'
            }).then((result) => {
                this.$Progress.start();
                if (result.value) {
                    this.form.delete('api/' + this.routeName + '/' + id).then(() => {
                        this.loadData();
                        this.$Progress.finish();
                        toast.fire({
                            icon: 'success',
                            title: 'تم الحذف بنجاح'
                        });
                        // Fire.$emit('afterCreate');
                    }).catch(() => {
                        toast.fire({
                            icon: "error",
                            title: "لم يتم الحذف"
                        });
                    });

                }
            });
        },
        loadData() {
            // if(this.$gate.isAdminOrAuthor()) {
            axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
            // }
        },
        loadPrice(row) {
            $("#showPrice").modal('show');
            axios.get('api/price?product_id=' + row.id).then(({data}) => (this.prices = data));
            this.product_name = row.name;
            this.product_id = row.id;
        },
        createData() {
            this.$Progress.start();
            $("#" + this.modalTitle).modal('hide');
            this.form
                .post("api/" + this.routeName)
                .then(() => {
                    // Fire.$emit("afterCreate");
                    $("#" + this.modalTitle).modal("hide");
                    toast.fire({
                        icon: "success",
                        title: "تم الحفظ بنجاح"
                    });
                    this.loadData();
                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ"
                    });
                });
        }
    },
    created() {

        axios.get('api/category?category=all').then(({data}) => (this.categories = data));
        axios.get('api/supplier').then(({data}) => (this.suppliers = data));

        this.loadData();

        for (let permission in this.permissions) {
            this.allPermissions[this.permissions[permission]] = this.permissions[permission];
        }
    }
}
</script>

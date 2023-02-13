<template>
    <div>
        <div class="row">
            <div class="col-4">
                <div v-if="'read_clients' in allPermissions" class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-6"><h3 class="card-title">{{ title }}</h3></div>
                                    <div class="col-6"><input type="text" placeholder="بحث ...." class="form-control"
                                                              v-model="searchClient" @keyup="searchResults"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card-tools text-right">
                                    <button v-if="'create_clients' in allPermissions" class="btn btn-primary"
                                            data-toggle="modal" @click="newModal()"
                                            :data-target="'#' + modalTitle"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="table_id" class="table table-bordered table-hover">
                            <thead>
                            <tr class="text-center">
                                <th>الاسم</th>
                                <th>رقم الهاتف</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in rows" :key="row.id">
                                <td>{{ row.name }}</td>
                                <td>{{ row.telephone }}</td>
                                <td>
                                    <a v-if="'update_clients' in allPermissions" href="#" @click="showDebts(row)"><i
                                        class="fa fa-plus blue"></i></a> / <a v-if="'update_clients' in allPermissions"
                                                                              href="#" :data-target="'#' + modalTitle"
                                                                              @click="editModal(row)"><i
                                    class="fa fa-edit blue"></i></a> / <a v-if="'delete_clients' in allPermissions"
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

            <div class="col-8" v-if="supplier.id > 0">
                <div class="card">
                    <div class="card-header"><h3>{{ supplier.name }}</h3></div>
                    <div class="card-body p-0">
                        <table id="table_id" class="table table-bordered table-hover">
                            <thead>
                            <tr class="text-center">
                                <th>إسم المنتج</th>
                                <th>السعر</th>
                                <th>الكميه</th>
                                <th>الجمله</th>
                                <th>المدفوع</th>
                                <th>المتبقي</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in debts" :key="row.id">
                                <td>{{ row.product_name }}</td>
                                <td>{{ row.purchase_price }}</td>
                                <td>{{ row.quantity }}</td>
                                <td>{{ row.purchase_price * row.quantity }}</td>
                                <td>{{ row.paid_price }}</td>
                                <td>{{ (row.purchase_price * row.quantity) - row.paid_price }}</td>
                                <td>
                                    <a v-if="'update_clients' in allPermissions" href="#" data-toggle="modal"
                                       @click="payModal(row)"
                                       :data-target="'#payModal'"><i class="fa fa-plus blue"></i></a> /
                                    <a v-if="'update_clients' in allPermissions" href="#" data-toggle="modal"
                                       @click="editDebtModal(row)"
                                       :data-target="'#payModal'"><i class="fa fa-plus blue"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->

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
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="إسم المورد"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.telephone" type="text" name="telephone" placeholder="رقم الهاتف"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('telephone') }">
                                <has-error :form="form" field="telephone"></has-error>
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

        <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editDebtMode ? updateDebtData() : createDebtData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" id="addNewLabel">
                                سداد</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">إسم المنتج</label>
                                        <input v-model="product_form.product_name" type="text" name="product_name"
                                               placeholder="إسم المنتج"
                                               class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">سعر الشراء</label>
                                        <input v-model="product_form.purchase_price" @change="calc" type="number" name="purchase_price"
                                               placeholder="سعر الشراء"
                                               class="form-control" :readonly="!editDebtMode">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">الكميه</label>
                                        <input v-model="product_form.quantity" @change="calc" type="number" name="quantity"
                                               placeholder="الكميه"
                                               class="form-control" :readonly="!editDebtMode">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">الجمله</label>
                                        <input v-model="amount" type="number" name="amount"
                                               placeholder="الجمله"
                                               class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">المدفوع</label>
                                        <input v-model="product_form.paid_price" @change="calc" type="number" name="paid_price"
                                               placeholder="المدفوع"
                                               class="form-control" :readonly="!editDebtMode">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">المتبقي</label>
                                        <input v-model="remaining" type="number" name="remaining"
                                               placeholder="المتبقي"
                                               class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-6" v-if="!editDebtMode">
                                    <div class="form-group">
                                        <label for="">السداد</label>
                                        <input v-model="debt_form.paid_price" type="number" name="paid_price"
                                               placeholder="المبلغ المدفوع"
                                               class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('paid_price') }">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" v-show="!editDebtMode" class="btn btn-primary">سداد</button>
                            <button type="submit" v-show="editDebtMode" class="btn btn-success">تعديل</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
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
            editDebtMode: false,
            modalTitle: 'suppliers',
            routeName: 'supplier',
            title: 'الموردين',
            subtitle: 'مورد',
            supplier: {},
            debts: {},
            currentProduct: {},
            searchClient: '',
            allPermissions: {},
            rows: {},
            amount: 0,
            remaining: '',
            form: new Form({
                id: '',
                name: '',
            }),
            product_form: new Form({
                id: '',
                paid_price:'',
                purchase_price:'',
                product_detail:'',
                product_id:'',
                quantity:'',
            }),
            debt_form: new Form({
                id: '',
                paid_price: ''
            })
        }
    },
    props: ['auth_id', 'permissions'],
    methods: {
        getResults(page = 1) {
            axios.get('api/' + this.routeName + '?page=' + page)
                .then(response => {
                    this.rows = response.data;
                });
        },
        searchResults() {
            if (this.searchClient != '') {
                axios.get('api/' + this.routeName + '?name=' + this.searchClient).then(({data}) => (this.rows = data));
            } else if (this.searchClient == '') {
                axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
            }
        },
        showDebts(row) {
            this.supplier = row;
            axios.get('api/debt?supplier=' + row.id).then(({data}) => (this.debts = data));
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
        updateDebtData() {
            this.$Progress.start();
            axios.put('api/debt/' + this.product_form.id, this.product_form).then(() => {
                $("#payModal").modal('hide');
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
            // $("#" + this.modalTitle).modal('show');
        },
        calc() {
            this.amount = this.product_form.purchase_price * this.product_form.quantity;
            this.remaining = this.amount - this.product_form.paid_price;
            this.$forceUpdate();

        },
        payModal(row) {
            this.editDebtMode = false;
            this.currentProduct = row;
            this.product_form = row;
            this.debt_form.id = row.id;
            $("#payModal").modal('show');
        },
        editModal(row) {
            this.editMode = true;
            this.form.reset();
            $("#" + this.modalTitle).modal('show');
            this.form.fill(row);
        },
        editDebtModal(row) {
            this.editDebtMode = true;
            this.currentProduct = row;
            this.product_form = row;

            this.amount = row.purchase_price * row.quantity;
            this.remaining = this.amount - row.paid_price;
            this.debt_form.id = row.id;
            $("#payModal").modal('show');
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
                if (result.value) {
                    this.$Progress.start();
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
        },
        createDebtData() {
            this.$Progress.start();
            $("#payModal").modal('hide');
            this.debt_form
                .post("api/debt")
                .then(() => {
                    $("#payModal").modal("hide");
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
        },
    },
    created() {

        this.loadData();

        for (let permission in this.permissions) {
            this.allPermissions[this.permissions[permission]] = this.permissions[permission];
        }

    }
}
</script>

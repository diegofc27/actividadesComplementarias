<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header ">
                    <div class="row justify-content-between">
                        <div class="mt-1">
                            <i class="fa fa-align-justify"></i> Grupos Q8
                            <button type="button" @click="openModal('q8', 'register')" class="btn btn-outline-primary rounded">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">

                        <div class="col-md-8">
                            <div class="input-group">
                                <select style="width: 30px;" class="form-control col-md-3" v-model="criteria">
                                    <option @click="search = null" value="nameQ8">Nombre</option>
                                    <option value="condition">Estado</option>
                                </select>
                                <input style="width: 100px;" type="text" v-if="criteria !='condition'" v-model="search" @keyup.enter="listQ8(1,search,criteria,period)" class="col-md-3 form-control" placeholder="Texto a buscar">
                                <select style="width: 50px;" v-else-if="criteria == 'condition'" v-model="search" @keyup.enter="listQ8(1,search,criteria, period)" class="form-control">
                                    <optgroup  v-if="criteria == 'condition'">
                                        <option value="" disabled selected>Seleccionar estado</option>
                                        <option value=1>Activado</option>
                                        <option value=0>Desactivado</option>
                                    </optgroup >
                                </select>

                                <label style="marginLeft: 20px;" for="vueSelect" class="mt-1 ml-1 mr-1"> <strong>Semestre: </strong></label>
                                <v-select id="vueSelect" :options="arrayPeriod" label="period" v-model="period" class="ml-1"></v-select> 

                                <button style="marginLeft: 20px;" type="submit" @click="listQ8(1,search,criteria,period.period)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            
                            </div>
                                
                        </div>

                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre del grupo</th>
                                <th>Número de estudiantes</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                                <th>Reporte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="q8 in arrayQ8" :key="q8.id">
                                
                                <td v-text="q8.nameQ8"></td>
                                <td v-text="q8.num_students"></td>
                                <td>
                                    <div v-if="q8.condition">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div> 
                                </td>
                                <td>
                                    <button v-if="q8.condition" type="button" @click="openModal('q8', 'update', q8)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="q8.condition">
                                        <button type="button" class="btn btn-danger btn-sm" @click="deactivateQ8(q8.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activateQ8(q8.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td>
                                    <a id="pdf" :href="'/admin/q8/pdf/'+q8.id"  class="nav-link" target="_blank">
                                    <!-- <a class="nav-link" href="#" @click="exportPDF(q8.id, q8.nameQ8)" download target="_blank"> -->
                                        <span class="badge badge-danger mr-3">
                                            PDF
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1,search,criteria);">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="changePage(page,search,criteria)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page+1,search,criteria)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'show': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="modalTitle"></h4>
                        <button type="button" class="close" @click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> 
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-10">
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre de grupo" style="width:400px !important;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Semestre</label>
                                <div class="col-md-10">
                                    <input type="text" v-model="period" class="form-control" placeholder="Número máximo de alumnos" style="width:400px !important;" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                            </div>
                            <div v-show="q8Error" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in q8ShowErrorMsg" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="actionType==1" class="btn btn-primary" @click="registerQ8()">Guardar</button>
                        <button type="button" v-if="actionType==2" class="btn btn-primary" @click="updateQ8()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!--Inicio del modal agregar actividad-->
        <div class="modal fade" tabindex="-1" :class="{'show': modalA}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="modalTitle"></h4>
                        <button type="button" class="close" @click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="actName" class="form-control" placeholder="Nombre de actividad">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                <div class="col-md-9">
                                    <v-select v-model="type" :options="arrayTypes"></v-select>
                                </div>
                            </div>
                            <div v-show="q8Error" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in q8ShowErrorMsg" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="actionType==3" class="btn btn-primary" @click="registerq8()">Añadir</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>
<script>
import { denodeify } from 'q';

    var horario = '';
    export default {
        data(){
            return{
                //Global Variables
                id_q8: 0,
                name: '',
                alumnos_inscritos: '',
                arrayQ8: [],
                actName: '',
                type: '',
                period:'',
                checkedNames: [],
                arrayPeriod: [],


                //Modal Variables
                modal: 0,
                modalA: 0,
                modalTitle: '',
                actionType: 0,
                arrayTypes: ['Deportiva', 'Cultural', 'Cívica'],

                //Validate Q8
                q8Error: 0,
                q8ShowErrorMsg: [],

                //Validate q8
                q8Error: 0,
                q8ShowErrorMsg: [],

                //Pagination
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset: 3,
                criteria: 'nameQ8',
                search: '',
            }
        },
        computed:{
            //Activated(?)
            isActived: function(){
                return this.pagination.current_page;
            },

            //Calculates the pagination elements
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        methods:{
            listQ8(page,search,criteria, period=this.period){
                let me = this;
                var url = '/admin/q8?page=' + page + '&search=' + search + '&criteria=' + criteria + '&period=' + period;
                console.log(url);
                
                //Categories table registries
                axios.get(url).then(function (response) {
                    // handle success
                    me.arrayQ8 = response.data.q8Groups.data;
                    me.pagination = response.data.pagination;
                    me.arrayPeriod = response.data.periods;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            href(id){
               window.location.href = 'admin/q8/pdf/'+id;
            }
            ,
            changePage(page, search, criteria){
                let me = this;

                //Update the current page
                me.pagination.current_page = page;

                //Send the current page view data request
                me.listQ8(page, search, criteria);
            },
            registerQ8(){
                if (this.validateQ8()) {
                    return;
                }
                let me = this;
                axios.post('/admin/q8/registrar', {
                    'name': this.name,
                    'period': this.period,
                }).then(function (response){
                    //Executed Succesfully
                    Swal.fire(
                    'Éxito!',
                    'Grupo creado satisfactoriamente',
                    'success'
                    );
                    me.closeModal();
                    me.listQ8(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            updateQ8(){
                if (this.validateQ8()) {
                    return;
                }
                let me = this;
                axios.post('/admin/q8/actualizar', {
                    'id': this.id_q8,
                    'name': this.name,
                    'period': this.period,
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito!',
                        text: 'El grupo se ha actualizado correctamente!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                    me.listQ8(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                    Swal.fire({
                    type: 'error',
                    title: 'Ups!',
                    text: 'Algo salió mal',
                    showConfirmButton: false,
                    timer: 2500
                    })
                });
            },
            // exportPDF(id, name){
            //     let me = this;
            //     var url = '/admin/q8/pdf/'+ id;
            //     axios.get(url, {}).then(function (response){
            //         //Executed Succesfully
            //         console.log(response);
            //         // Swal.fire(
            //         // 'Hiciste click!',
            //         // 'Funcionalidad en desarrollo',
            //         // 'success'
            //         // );
            //         me.closeModal();
            //         me.listQ8(1,'','name');
            //     }).catch(function (error) {
            //         console.log(error);
            //     });
            // },
            deactivateQ8(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Desactivar Grupo',
                text: "¿Esta seguro que quiere desactivar este grupo?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.put('/admin/q8/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listQ8(1,'','name');

                        swalWithBootstrapButtons.fire(
                        'Eliminado Satisfactoriamente!',
                        'El grupo ha sido desactivado satisfactoriamente.',
                        'success'
                        )
                    }).catch(function (error) {
                            console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
            activateQ8(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Activar Grupo',
                text: "¿Esta seguro que quiere activar este grupo?",
                type: 'success',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.put('/admin/q8/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listQ8(1,'','name');

                        swalWithBootstrapButtons.fire(
                        'Activado Satisfactoriamente!',
                        'El grupo ha sido activado satisfactoriamente.',
                        'success'
                        )
                    }).catch(function (error) {
                            console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
            validateQ8(){
                this.q8Error=0;
                this.q8ShowErrorMsg = [];

                if(!this.name) this.q8ShowErrorMsg.push('El Nombre del grupo no puede estar vacío.');
                if (this.q8ShowErrorMsg.length) this.q8Error = 1;
                
                return this.q8Error;
            },
            closeModal(){
                this.modal = 0;
                this.modalA = 0;
                this.modalTitle = '';
                this.name = '';
                this.id_teacher = '';
                this.max_students = 0;
                this.q8Error = 0;
                this.q8Error = 0;
            },
            openModal(model, action, data = []){
                switch (model) {
                    case "q8":
                    {
                        switch (action) {
                            case 'register':
                            {
                                //Show Modal
                                this.modal = 1;
                                this.modalTitle = 'Registrar Grupo';
                                this.name = '';
                                this.id_teacher = '';
                                this.max_students = '';
                                this.actionType = 1;
                                var today = new Date();
                                let sufix;
                                if(today.getMonth <= 5) sufix = '1';
                                else sufix = '1';
                                this.period = today.getFullYear().toString() + sufix;
                                break;
                            }
                            case 'update':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Actualizar Grupo';
                                this.actionType = 2;
                                this.id_q8 = data['id'];
                                this.name = data['nameQ8'];

                                break;
                            }
                        }
                    }
                }
            },
        },
        mounted() {
            var today = new Date();
            let sufix;
            if(today.getMonth <= 5) sufix = '1';
            else sufix = '1';
            this.period = today.getFullYear().toString() + sufix;
            this.listQ8(1,this.search,this.criteria, this.period);
            console.log(this);
            // console.log('Component mounted.')
        }
    }


</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .show{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>

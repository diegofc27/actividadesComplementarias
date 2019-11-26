
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
                            <i class="fa fa-align-center"></i> Grupos
                            <button type="button" @click="openModal('group', 'register')" class="btn btn-success rounded ml-1">
                                <i class="icon-plus"></i>&nbsp;Nuevo Grupo
                            </button>
                        </div>
                        <div class="mt-1">
                            <button type="button" @click="openModal('activity', 'add')" class="btn btn-outline-primary rounded">
                                <i class="icon-plus"></i>&nbsp;Nueva Disciplina
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                    <option @click="search = ''" value="name">Nombre</option>
                                </select>
                                <input type="text" v-model="search" @keyup.enter="listGroup(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listGroup(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nombre del grupo</th>
                                <th>Nombre del maestro</th>
                                <th>Número máximo de alumnos</th>
                                <th>Horario</th>
                                <th>Estado</th>
                                <th>Lista</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="group in arrayGroup" :key="group.id">
                                
                                <td><a id="link" href="#" v-text="group.name" @click="openModal('group', 'info', group)"></a></td>
                                <td v-text="group.teacher_name + ' ' + group.teacher_lastName1 + ' ' + group.teacher_lastName2"></td>
                                <td v-text="group.max_students"></td>
                                <td v-text="group.schedule"></td>
                                <td>
                                    <div v-if="group.condition">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div> 
                                </td>
                                <td><a id="pdf" :href="'/registro/'+group.id"  class="nav-link" target="_blank">
                                    <!-- <a class="nav-link" href="#" @click="exportPDF(q8.id, q8.nameQ8)" download target="_blank"> -->
                                        <span class="badge badge-danger mr-3">
                                            PDF
                                        </span>
                                    </a></td>
                                <td>
                                    <button type="button" @click="openModal('group', 'update', group)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="group.condition">
                                        <button type="button" class="btn btn-danger btn-sm" @click="deactivateGroup(group.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button type="button" @click="openModal('group', 'info', group)" class="btn btn-sm fa fa-info btn-info">
                                            Info
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activateGroup(group.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
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
                            <div v-if="actionType != 4">
                                <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Disciplina</label>
                                    <div class="col-md-8">
                                        <v-select id="vueSelect" label="name" :options="arrayActivities" v-model="activity" style="width:400px !important;"></v-select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de grupo" style="width:400px !important;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Maestro</label>
                                        <div class="col-md-10">
                                            <v-select id="vueSelect" label="name" :options="arrayTeachers" v-model="teacher" style="width:400px !important;"></v-select>                                        
                                        </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Alumnos</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="max_students" class="form-control" placeholder="Número máximo de alumnos" style="width:400px !important;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Horario</label>
                                    <div class="col-md-10">
                                        <div class="container">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="checkbox" id="lunes" value="lunes" v-model="checkedNames">
                                                        <label for="lunes">Lun</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="lunese" for="lunesa">Desde:</label>
                                                        <v-timepicker class="lunese" id="lunese" :format="yourFormat" v-model="yourData1"></v-timepicker>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="luness" for="lunesb">Hasta:</label>
                                                        <v-timepicker class="luness" :format="yourFormat" v-model="yourData2"></v-timepicker>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="checkbox" id="martes" value="martes" v-model="checkedNames">
                                                        <label for="martes">Mar</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="martese" for="martes">Desde:</label>
                                                        <v-timepicker class="martese" :format="yourFormat" v-model="yourData3"></v-timepicker>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="martess" for="martes">Hasta:</label>
                                                    <v-timepicker class="martess" :format="yourFormat" v-model="yourData4"></v-timepicker>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="checkbox" id="miercoles" value="miercoles" v-model="checkedNames">
                                                        <label for="miercoles">Miér</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="miercolese" for="miercoles">Desde:</label>
                                                        <v-timepicker class="miercolese" :format="yourFormat" v-model="yourData5"></v-timepicker>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="miercoless" for="miercoles">Hasta:</label>
                                                    <v-timepicker class="miercoless" :format="yourFormat" v-model="yourData6"></v-timepicker>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="checkbox" id="jueves" value="jueves" v-model="checkedNames">
                                                        <label for="jueves">Jue</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="juevese" for="jueves">Desde:</label>
                                                        <v-timepicker class="juevese" :format="yourFormat" v-model="yourData7"></v-timepicker>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="juevess" for="jueves">Hasta:</label>
                                                    <v-timepicker class="juevess" :format="yourFormat" v-model="yourData8"></v-timepicker>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="checkbox" id="viernes" value="viernes" v-model="checkedNames">
                                                        <label for="viernes">Vie</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="viernese" for="viernes">Desde:</label>
                                                        <v-timepicker class="viernese" :format="yourFormat" v-model="yourData9"></v-timepicker>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="vierness" for="viernes">Hasta:</label>
                                                    <v-timepicker class="vierness" :format="yourFormat" v-model="yourData10"></v-timepicker>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="checkbox" id="sabado" value="sabado" v-model="checkedNames">
                                                        <label for="sabado">Sáb</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="sabadoe" for="sabado">Desde:</label>
                                                        <v-timepicker class="sabadoe" :format="yourFormat" v-model="yourData11"></v-timepicker>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="sabados" for="sabado">Hasta:</label>
                                                    <v-timepicker class="sabados" :format="yourFormat" v-model="yourData12"></v-timepicker>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nombre del grupo</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de grupo" style="width:400px" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Disciplina</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="activity" class="form-control" placeholder="Nombre de grupo" style="width:400px" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Maestro</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="teacher" class="form-control" placeholder="Nombre de grupo" style="width:400px" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Horario</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="schedule" class="form-control" placeholder="Nombre de grupo" style="width:400px" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Máximo de alumnos</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="max_students" class="form-control" placeholder="Nombre de grupo" style="width:400px" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Alumnos inscritos</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="num_students" class="form-control" placeholder="Nombre de grupo" style="width:400px" disabled>
                                    </div>
                                </div>
                            </div>
                            <div v-show="groupError" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in groupShowErrorMsg" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="actionType==1" class="btn btn-primary" @click="registerGroup()">Guardar</button>
                        <button type="button" v-if="actionType==2" class="btn btn-primary" @click="updateGroup()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!--Inicio del modal agregar disciplina-->
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
                                    <input type="text" v-model="actName" class="form-control" placeholder="Nombre de disciplina">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                <div class="col-md-9">
                                    <v-select v-model="type" :options="arrayTypes"></v-select>
                                </div>
                            </div>
                            <div v-show="activityError" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in activityShowErrorMsg" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="actionType==3" class="btn btn-primary" @click="registerActivity()">Añadir</button>
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

    var horario = '';
    export default {
        data(){
            return{
                //Global Variables
                group_id: 0,
                name: '',
                id_teacher: '',
                id_activity:'',
                max_students: '',
                arrayGroup: [],
                arrayActivities: [],
                arrayTeachers: [],
                activity: '',
                teacher: '',
                actName: '',
                type: '',
                checkedNames: [],
                schedule: '',
                horario: '',
                num_students:'',

                //Modal Variables
                modal: 0,
                modalA: 0,
                modalTitle: '',
                actionType: 0,
                arrayTypes: ['Deportiva', 'Cultural', 'Cívica'],

                //Validate Group
                groupError: 0,
                groupShowErrorMsg: [],

                //Validate Activity
                activityError: 0,
                activityShowErrorMsg: [],

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
                criteria: 'name',
                search: '',
                yourFormat: 'HH:mm',
                yourData1: {
                    HH: '00',
                    mm: '00',
                },
                yourData2: {
                    HH: '00',
                    mm: '00',
                },
                yourData3: {
                    HH: '00',
                    mm: '00',
                },
                yourData4: {
                    HH: '00',
                    mm: '00',
                },
                yourData5: {
                    HH: '00',
                    mm: '00',
                },
                yourData6: {
                    HH: '00',
                    mm: '00',
                },
                yourData7: {
                    HH: '00',
                    mm: '00',
                },
                yourData8: {
                    HH: '00',
                    mm: '00',
                },
                yourData9: {
                    HH: '00',
                    mm: '00',
                },
                yourData10: {
                    HH: '00',
                    mm: '00',
                },
                yourData11: {
                    HH: '00',
                    mm: '00',
                },
                yourData12: {
                    HH: '00',
                    mm: '00',
                }
                
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
            selectActivity(){
                let me = this;
                axios.get('/admin/grupo/selectActivity').then(function (response){
                    //Executed Succesfully
                    me.arrayActivities = response.data.activities;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            selectTeacher(){
                let me = this;
                axios.get('/admin/grupo/selectTeacher').then(function (response){
                    //Executed Succesfully
                    response.data.teachers.forEach(teacher => {
                        me.arrayTeachers.push({
                            'id': teacher.id,
                            'name': teacher.name + ' ' + teacher.lastName1 + ' ' + teacher.lastName2
                        });
                    }); 
                }).catch(function (error) {
                    console.log(error);
                });
            },
            listGroup(page,search,criteria){
                let me = this;
                var url = '/admin/grupo?page=' + page + '&search=' + search + '&criteria=' + criteria;
                
                //Categories table registries
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    me.arrayGroup = respuesta.groups.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            changePage(page, search, criteria){
                let me = this;

                //Update the current page
                me.pagination.current_page = page;

                //Send the current page view data request
                me.listGroup(page, search, criteria);
            },
            calc_schedule(){

                if (document.getElementById("lunes").checked) {                    
                    horario += "lun " + String(this.yourData1.HH+':'+this.yourData1.mm) +' a '+ String(this.yourData2.HH+':'+this.yourData2.mm +' ')
                }
                if (document.getElementById("martes").checked) {                    
                    horario += "mar " + String(this.yourData3.HH+':'+this.yourData3.mm) +' a '+ String(this.yourData4.HH+':'+this.yourData4.mm+' ')
                }
                if (document.getElementById("miercoles").checked) {                    
                    horario += "mie " + String(this.yourData5.HH+':'+this.yourData5.mm) +' a '+ String(this.yourData6.HH+':'+this.yourData6.mm+' ')
                }
                if (document.getElementById("jueves").checked) {                    
                    horario += "jue " + String(this.yourData7.HH+':'+this.yourData7.mm) +' a '+ String(this.yourData8.HH+':'+this.yourData8.mm+' ')
                }
                if (document.getElementById("viernes").checked) {                    
                    horario += "vie " + String(this.yourData9.HH+':'+this.yourData9.mm) +' a '+ String(this.yourData10.HH+':'+this.yourData10.mm+' ')
                }
                if (document.getElementById("sabado").checked) {                    
                    horario += "sab " + String(this.yourData11.HH+':'+this.yourData11.mm) +' a '+ String(this.yourData12.HH+':'+this.yourData12.mm+' ')
                }
            },
            registerGroup(){
                if (this.validateGroup()) {
                    return;
                }
                this.calc_schedule();
                let me = this;
                axios.post('/admin/grupo/registrar', {
                    'name': this.name,
                    'id_teacher': this.teacher.id,
                    'max_students': this.max_students,
                    'id_activity': this.activity.id,
                    'schedule': horario
                    // + String(this.yourData1) + String(this.yourData2)
                }).then(function (response){
                    //Executed Succesfully
                    Swal.fire(
                    'Éxito!',
                    'Grupo creado satisfactoriamente',
                    'success'
                    );
                    me.closeModal();
                    me.listGroup(1,'','name');
                }).catch(function (error) {
                        console.log(error);
                });
            },
            registerActivity(){
                if (this.validateActivity()) {
                    return;
                }
                let me = this;
                axios.post('/admin/actividad/registrar', {
                    'name': this.actName,
                    'type': this.type
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito!',
                        text: 'La disciplina se ha creado correctamente!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                    me.listGroup(1,'','name');
                    me.selectActivity();
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
            updateGroup(){
                if (this.validateGroup()) {
                    return;
                }
                let me = this;
                axios.post('/admin/grupo/actualizar', {
                    'id': this.id,
                    'name': this.name,
                    'id_teacher': this.id_teacher,
                    'max_students': this.max_students,
                    'id_activity': this.id_activity
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
                    me.listGroup(1,'','name');
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
            deactivateGroup(id){
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

                    axios.put('/admin/grupo/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listGroup(1,'','name');

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
            activateGroup(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Activar Grupo',
                text: "¿Esta seguro que quiere activar esta grupo?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.put('/admin/grupo/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listGroup(1,'','name');

                        swalWithBootstrapButtons.fire(
                        'Activado!',
                        'El registro ha sido activado satisfactoriamente.',
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
            validateGroup(){
                this.groupError=0;
                this.groupShowErrorMsg = [];

                if(!this.name) this.groupShowErrorMsg.push('El Nombre del grupo no puede estar vacío.');
                if (this.groupShowErrorMsg.length) this.groupError = 1;
                
                return this.groupError;
            },
            validateActivity(){
                this.activityError=0;
                this.activityShowErrorMsg = [];

                if(!this.actName) this.activityShowErrorMsg.push('El nombre de la disciplina no puede estar vacío.');
                if (this.activityShowErrorMsg.length) this.activityError = 1;
                
                return this.activityError;
            },
            closeModal(){
                this.modal = 0;
                this.modalA = 0;
                this.modalTitle = '';
                this.name = '';
                this.id_teacher = '';
                this.max_students = 0;
                this.groupError = 0;
                this.activityError = 0;
            },
            openModal(model, action, data = []){
                switch (model) {
                    case "group":
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
                                break;
                            }
                            case 'update':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Actualizar Grupo';
                                this.actionType = 2;
                                this.id = data['id'];
                                this.name = data['name'];
                                this.teacher = data['teacher_name'] + ' ' + data['teacher_lastName1'] + ' ' + data['teacher_lastName2'];
                                this.id_teacher = data['id_teacher']
                                this.activity = data['activity_name'];
                                this.id_activity = data['id_activity'];
                                this.id_teacher = data['id_activity']
                                this.max_students = data['max_students']

                                var lun = this.schedule.indexOf('lun');
                                var mar = this.schedule.indexOf('mar');
                                var mie = this.schedule.indexOf('mie');
                                var jue = this.schedule.indexOf('jue');
                                var vie = this.schedule.indexOf('vie');
                                var sab = this.schedule.indexOf('sab');

                                var lunentrada = this.schedule.substring(lun+4, lun+9);
                                var lunsalida = this.schedule.substring(lun+12,lun+17);

                                var marentrada = this.schedule.substring(mar+4, mar+9);
                                var marsalida = this.schedule.substring(mar+12,mar+17);

                                var mieentrada = this.schedule.substring(mie+4, mie+9);
                                var miesalida = this.schedule.substring(jue+12,jue+17);

                                var jueentrada = this.schedule.substring(jue+4, jue+9);
                                var juesalida = this.schedule.substring(jue+12,jue+17);

                                var vieentrada = this.schedule.substring(vie+4, vie+9);
                                var viesalida = this.schedule.substring(vie+12,vie+17);

                                var sabentrada = this.schedule.substring(sab+4, sab+9);
                                var sabsalida = this.schedule.substring(sab+12,sab+17);

                                if (this.schedule.includes('lun')){
                                    yourData1:{
                                        HH:lunentrada.substring(0,2);
                                        mm:lunentrada.substring(3,5);
                                    }
                                    yourData2:{
                                        HH:lunsalida.substring(0,2);
                                        mm:lunsalida.substring(3,5);
                                    }
                                    $("#lunes").checked(true);
                                    $(".lunese").show();
                                    $(".luness").show();
                                }


                                break;
                            }
                            case 'info':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Información del Grupo';
                                this.id = data['id'];
                                this.actionType = 4;
                                this.name = data['name'];
                                this.teacher = data['teacher_name'];
                                this.period = data['period'];
                                this.schedule = data['schedule'];
                                this.max_students = data['max_students'];
                                this.num_students = data['num_students'];
                                this.activity = data['activity_name'];
                                break;
                            }
                        }
                    }
                    case "activity":
                    {
                        switch (action) {
                            case 'add':
                            {
                                //Show Modal
                                this.modalA = 1;
                                this.modalTitle = 'Registrar Disciplina';
                                this.actName = '';
                                this.type = 'Seleccionar tipo de disciplina';
                                this.actionType = 3;
                                // this.arrayTypes =;
                                break;
                            }
                        }
                    }
                }
            },
        },
        mounted() {
            this.selectActivity();
            this.selectTeacher();
            this.listGroup(1,this.search,this.criteria);
        }
    }

    $(function() {
        $(".lunese").hide();
        $(".luness").hide();
        $(".martese").hide();
        $(".martess").hide();
        $(".miercolese").hide();
        $(".miercoless").hide();
        $(".juevese").hide();
        $(".juevess").hide();
        $(".viernese").hide();
        $(".vierness").hide();
        $(".sabadoe").hide();
        $(".sabados").hide();
    });


$(document).ready(function() {
  $(".lunes").click(function(){
        $(".lunese").toggle();
        $(".luness").toggle();
    });

    $(".martes").click(function(){
        $(".martese").toggle();
        $(".martess").toggle();
    });

    $(".miercoles").click(function(){
        $(".miercolese").toggle();
        $(".miercoless").toggle();
    });

    $(".jueves").click(function(){
        $(".juevese").toggle();
        $(".juevess").toggle();
    });

     $(".viernes").click(function(){
        $(".viernese").toggle();
        $(".vierness").toggle();
    });

     $(".sabado").click(function(){
        $(".sabadoe").toggle();
        $(".sabados").toggle();
    });
});

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

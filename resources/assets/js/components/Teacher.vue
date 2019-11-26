<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Maestros
                    <button type="button" @click="openModal('teacher', 'register')" class="btn btn-success rounded">
                        <i class="icon-plus"></i>&nbsp; Nuevo Maestro
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="input-group">
                                <select style="width: 30px;" class="form-control col-md-3" v-model="criteria">
                                    <option value="name">Nombre</option>
                                    <option value="email">No de Control</option>
                                    <option value="condition">Estado</option>
                                </select>
                                <input style="width: 100px;" type="text" v-if="criteria =='name' || criteria == 'email'" v-model="search" @keyup.enter="listTeacher(1,search,criteria, period)" class="form-control" placeholder="Texto a buscar">
                                <select style="width: 50px;" v-else-if="criteria =='id_role' || criteria == 'condition'" v-model="search" @keyup.enter="listTeacher(1,search,criteria, period)" class="form-control">
                                    <optgroup  v-if="criteria == 'condition'">
                                        <option value="" disabled selected>Seleccionar estado</option>
                                        <option value=1>Activado</option>
                                        <option value=0>Desactivado</option>
                                    </optgroup >
                                </select>

                                <label style="marginLeft: 20px;" for="vueSelect" class="form-control col-md-4">Semestre</label>
                                <v-select style="" id="vueSelect" :options="arrayPeriod" label="period" v-model="period"></v-select> 

                                <button style="marginLeft: 20px;" type="submit" @click="listTeacher(1,search,criteria, period.period)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>   
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre del Maestro</th>
                                <th>Clave</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="teacher in arrayTeacher" :key="teacher.id">
                                <td><a id="link" href="#" v-text="teacher.name + ' ' + teacher.lastName1 + ' ' + teacher.lastName2" @click="openModal('teacher', 'info', teacher)"></a></td>
                                <td v-text="teacher.email"></td>
                                <td>
                                    <div v-if="teacher.period == actualPeriod">
                                        <button v-if="teacher.condition" type="button" @click="openModal('teacher', 'update', teacher)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button>
                                        <template>
                                            <button v-if="teacher.condition" type="button" class="btn btn-danger btn-sm" @click="deactivateTeacher(teacher.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-info btn-sm" @click="activateTeacher(teacher.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                        <button v-if="teacher.condition" type="button" @click="openModal('teacher', 'changePsw', teacher)" class="btn fa fa-lock btn-info">
                                                
                                        </button>
                                    </div>
                                    <button v-if="teacher.condition" type="button" @click="openModal('teacher', 'info', teacher)" class="btn btn-sm fa fa-info btn-info">
                                            Info
                                    </button>
                                    <!-- <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activateTeacher(teacher.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template> -->
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
        <div class="modal fade" tabindex="-1" :class="{'show': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña Actual</label>
                                    <div class="col-md-9">
                                        <input v-if="actionType==1 || actionType==2" type="text" v-model="name" class="form-control" placeholder="Nombre del maestro">
                                        <input v-else-if="actionType==3" type="password" v-model="currentPass" class="form-control" placeholder="Contraseña Actual">
                                    </div>
                                </div>
                                <div v-if="actionType==1 || actionType==2"  class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Primer Apellido</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="lastName1" class="form-control" placeholder="Primer Apellido">
                                    </div>
                                </div>
                                <div v-if="actionType==1 || actionType==2"  class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Segundo Apellido</label>
                                    <div class="col-md-9">
                                        <input  type="text" v-model="lastName2" class="form-control" placeholder="Segundo Apellido">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Clave el Maestro</label>
                                    <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña Nueva</label>
                                    <div class="col-md-9">
                                        <input v-if="actionType==1 || actionType==2" type="text" v-model="username" class="form-control" placeholder="Clave del maestro">
                                        <input v-else-if="actionType==3" type="password" v-model="newPass" class="form-control" placeholder="Contraseña Nueva">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="actionType==1">
                                    <label class="col-md-3 form-control-label" for="text-input">Contraseña</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Contraseña">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="actionType==3">
                                    <label class="col-md-3 form-control-label" for="text-input">Confirmar Contraseña</label>
                                    <div class="col-md-9">
                                        <input v-if="actionType==3" type="password" v-model="confirmPass" class="form-control" placeholder="Confirmar Contraseña">
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="3" v-model="name" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Clave del maestro</label>
                                    <div class="col-md-9">
                                        <input type="number" maxlength="3" v-model="username" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Grupos Impartidos</label>
                                    <ul>
                                        <div v-for="group in arrayGroupByTeacher" v-bind:key="group.id_group" class="col-md-9">
                                            <li v-if="group.id_teacher == teacher_id">
                                                <!-- <label class="col-md-9 form-control-label" placeholder="Sin Grupo">{{group.name_group}}</label> -->
                                                <input v-model="group.name_group" type="text" maxlength="3" class="form-control" placeholder="Sin Grupo" disabled>
                                            </li>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <!--<div class="form-group row"  v-if="actionType==1 || actionType==2">
                                <label class="col-md-3 form-control-label" for="text-input">Grupo</label>
                                <div class="col-md-9">
                                    <v-select id="vueSelect" label="name" :options="arrayGroups" v-model="group"></v-select>
                                </div>
                            </div>-->
                            <!-- <div v-show="teacherError" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in teacherShowErrorMsg" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div> -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="actionType==1" class="btn btn-primary" @click="registerTeacher()">Guardar</button>
                        <button type="button" v-if="actionType==2" class="btn btn-primary" @click="updateTeacher()">Actualizar</button>
                        <button type="button" v-if="actionType==3" class="btn btn-primary" @click="pVal()">Cambiar Contraseña</button>
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
    export default {
        data(){
            return{

                //Global Variables
                teacher_id: '',
                name: '',
                lastName1: '',
                lastName2: '',
                username: '',
                password: '',
                currentPass: '',
                newPass: '',
                period:'',
                actualPeriod:'',
                confirmPass:'',
                arrayTeacher: [],
                arrayPeriod: [],
                arrayGroupByTeacher: [],

                //Modal Variables
                modal: 0,
                modalTitle: '',
                actionType: 0,
                arrayGroups: [],

                //Validate Teacher
                teacherError: 0,
                teacherShowErrorMsg: [],

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
                search: ''
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
            listTeacher(page,search,criteria,period = this.period){
                let me = this;
                var url = '/admin/maestro?page=' + page + '&search=' + search + '&criteria=' + criteria + '&period=' + period;
                
                //Categories table registries
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    me.arrayTeacher = respuesta.teachers.data;
                    me.pagination = respuesta.pagination;
                    me.arrayPeriod = respuesta.periods;
                    me.arrayGroupByTeacher = respuesta.groupsByTeacher;
                    // console.log(response);
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
                me.listTeacher(page, search, criteria);
            },
            registerTeacher(){

                if (this.validateTeacher()) {
                    toastr.warning(this.teacherShowErrorMsg, 'Atención!', {timeOut: 5000});
                    return;
                }

                let me = this;

                axios.post('/admin/maestro/registrar', {
                    'username': this.username,
                    'password': this.password,
                    'name': this.name,
                    'lastName1': this.lastName1,
                    'lastName2': this.lastName2
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                    type: 'success',
                    title: 'Éxito!',
                    text: 'Tu registro se ha creado correctamente!',
                    showConfirmButton: false,
                    timer: 2500
                    })
                    me.listTeacher(1,'','name');
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
            updateTeacher(){

                if (this.validateTeacherUp()) {
                    toastr.warning(this.teacherShowErrorMsg, 'Atención!', {timeOut: 5000});
                    return;
                }

                let me = this;
                axios.put('/admin/maestro/actualizar', {
                    'name': this.name,
                    'username': this.username,
                    'password':this.password,
                    'id': this.teacher_id
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                                        Swal.fire({
                    type: 'success',
                    title: 'Éxito!',
                    text: 'Tu registro se ha actualizado correctamente!',
                    showConfirmButton: false,
                    timer: 2500
                    })
                    me.listTeacher(1,'','name');
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
            changePassword(id_user){
                let me = this;
                axios.post('/admin/usuario/chgPs', {
                    'id_user': id_user,
                    'currentPassword': this.currentPass,
                    'newPass': this.newPass,
                    'confirmPass': this.confirmPass,
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito!',
                        text: 'Contraseña actualizada correctamente!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                    me.listTeacher(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                    Swal.fire({
                    type: 'error',
                    title: 'Ups!',
                    text: 'Algo salió mal',
                    showConfirmButton: false,
                    timer: 3000
                    })
                });
            },
            pVal(){
                let me = this;
                axios.post('/admin/usuario/pVal', {
                    'currentPassword': this.currentPass,
                    'newPass': this.newPass,
                    'confirmPass': this.confirmPass,
                    'id_user': this.id_user
                }).then(function(response) {
                    //Executed Succesfully
                    if (response.data == '1') {
                        me.changePassword(me.id_user);
                    } else {
                        Swal.fire({
                        type: 'error',
                        title: 'Ups!',
                        text: response.data,
                        showConfirmButton: false,
                        timer: 2500
                        })
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validateTeacher(){
                this.teacherError=0;
                this.teacherShowErrorMsg = [];
                var expAlpha = /((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i;
                var expNum = /^\d+$/i;
                var expLet = /^[ A-Za-z]+$/;
                //Nombre y apellido
                if(!this.name) this.teacherShowErrorMsg.push('El Nombre del Maestro no puede estar vacío.');
                else if(!this.lastName1 && !this.lastName2) this.teacherShowErrorMsg.push('Debe ingresar al menos un apellido.');
                else if(!this.name.match(expLet)) this.teacherShowErrorMsg.push('Caracter inválido en Nombre.');
                else if((!this.lastName1.match(expLet) && this.lastName1) || (!this.lastName2.match(expLet) && this.lastName2)) this.teacherShowErrorMsg.push('Caracter inválido en Apellidos.');
                else if(this.name.length > 30  || this.lastName1.length > 30 || this.lastName2.length > 30) this.teacherShowErrorMsg.push('Nombre o apellidos demasiado grandes.');
                //Clave
                else if(!this.username) this.teacherShowErrorMsg.push('El número de control no pueden estar vacíos.');
                else if(!this.username.match(expNum)) this.teacherShowErrorMsg.push('Solo números en Clave del Maestro.');
                else if(this.username.length > 4) this.teacherShowErrorMsg.push('Clave del Maestro muy grande');
                //Contraseña
                else if(!this.password) this.teacherShowErrorMsg.push('La contraseña no puede estar vacía.');
                else if(!this.password.match(expAlpha)) this.teacherShowErrorMsg.push('Solo números y letras son permitidos en contraseña.');
                else if(this.password.length >190) this.teacherShowErrorMsg.push('La contraseña es muy grande.');

                if (this.teacherShowErrorMsg.length) this.teacherError = 1;
                
                return this.teacherError;
            },
            validateTeacherUp(){
                this.teacherError=0;
                this.teacherShowErrorMsg = [];
                var expAlpha = /((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i;
                var expNum = /^\d+$/i;
                var expLet = /^[ A-Za-z]+$/;
                //Nombre y apellido
                if(!this.name) this.teacherShowErrorMsg.push('El Nombre del Alumno no puede estar vacío.');
                else if(!this.lastName1 && !this.lastName2) this.teacherShowErrorMsg.push('Debe ingresar al menos un apellido.');
                else if(!this.name.match(expLet)) this.teacherShowErrorMsg.push('Caracter inválido en Nombre.');
                else if((!this.lastName1.match(expLet) && this.lastName1) || (!this.lastName2.match(expLet) && this.lastName2)) this.teacherShowErrorMsg.push('Caracter inválido en Apellidos.');
                else if(this.name.length > 30  || this.lastName1.length > 30 || this.lastName2.length > 30) this.teacherShowErrorMsg.push('Nombre o apellidos demasiado grandes.');
                //Clave del Maestro
                else if(!this.username) this.teacherShowErrorMsg.push('La Clave del Maestro no pueden estar vacíos.');
                else if(!this.username.match(expNum)) this.teacherShowErrorMsg.push('Solo números en Clave del Maestro.');
                else if(this.username.length > 4) this.teacherShowErrorMsg.push('Clave del Maestro muy grande');

                if (this.teacherShowErrorMsg.length) this.teacherError = 1;
                
                return this.teacherError;
            },
            closeModal(){
                this.modal = 0;
                this.modalTitle = '';
                this.name = '';
                this.username = '';
                this.password = '';
                this.teacherError = 0;
            },
            openModal(model, action, data = []){
                switch (model) {
                    case "teacher":
                    {
                        switch (action) {
                            case 'register':
                            {
                                //Show Modal
                                this.modal = 1;
                                this.modalTitle = 'Registrar Maestro';
                                this.name = '';
                                this.username = '';
                                this.password = '';

                                this.actionType = 1;
                                break;
                            }
                            case 'update':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Actualizar Maestro';
                                this.teacher_id = data['id'];
                                this.name = data['name'];
                                this.username = data['email'];
                                this.password = data['password'];
                                
                                this.actionType = 2;
                                break;

                            }
                            case 'changePsw':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Cambiar Contraseña';
                                this.newPass = '';
                                this.currentPass = '';
                                this.confirmPass = '';
                                this.actionType = 3;
                                this.id_user = data['id_user']
                                break;
                            }
                             case 'info':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Información del Maestro';
                                this.actionType = 4;
                                this.teacher_id = data['id'];
                                this.id_user = data['id_user'];
                                this.name = data['name'];
                                this.username = data['email'];
                                break;
                            }
                        }
                    }
                }
            },
            deactivateTeacher(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Desactivar Maestro',
                text: "Esta seguro que quiere desactivar este maestro?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.post('/admin/maestro/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listTeacher(1,'','name');

                        swalWithBootstrapButtons.fire(
                        'Eliminado Satisfactoriamente!',
                        'El registro ha sido desactivado satisfactoriamente.',
                        'success'
                        )
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

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
            activateTeacher(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })
                swalWithBootstrapButtons.fire({
                title: 'Activar Maestro',
                text: "¿Está seguro que quiere activar de nuevo este maestro?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.post('/admin/maestro/toggle', {
                        'id': id,
                    }).then(function (response){
                        //Executed Succesfully
                        me.listTeacher(1,'','name');
                        swalWithBootstrapButtons.fire(
                        'Activado Satisfactoriamente!',
                        'El registro ha sido activado satisfactoriamente.',
                        'success'
                        )
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

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
        },
        mounted() {
            let today = new Date();
            let sufix;
            if(today.getMonth <= 5) sufix = '1';
            else sufix = '1';
            this.period = today.getFullYear().toString() + sufix;
            this.actualPeriod = today.getFullYear().toString() + sufix;
            this.listTeacher(1,this.search,this.criteria, this.period);
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

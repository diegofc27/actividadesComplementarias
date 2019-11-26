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
                    <button type="button" @click="openModal('student', 'register')"  class="btn btn-success rounded">
                        <i class="icon-plus"></i>&nbsp; Nuevo Alumno
                    </button>

                    <!-- <div class="text-right"> -->
                        <button type="button"  onclick="javascript:window.location.href='/importExportView'" class="text-right btn btn-outline-primary rounded">
                            <i class="icon-plus"></i>&nbsp; Importar Alumnos
                        </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <select style="width: 30px;" class="form-control col-md-3" v-model="criteria">
                                    <option value="name">Nombre</option>
                                    <option value="email">No de Control</option>
                                    <option value="nameQ8">Grupo de Q8</option>
                                    <option value="condition">Estado</option>
                                </select>
                                <input style="width: 100px;" type="text" v-if="criteria !='condition'" v-model="search" @keyup.enter="listStudent(1,search,criteria,period)" class="col-md-3 form-control ml-2" placeholder="Texto a buscar">
                                <select style="width: 30px;" v-else-if="criteria == 'condition'" v-model="search" @keyup.enter="listStudent(1,search,criteria, period)" class="form-control col-md-3 ml-2">
                                    <optgroup  v-if="criteria == 'condition'">
                                        <option value="" disabled selected>Seleccionar estado</option>
                                        <option value=1>Activado</option>
                                        <option value=0>Desactivado</option>
                                    </optgroup >
                                </select>

                                <label style="marginLeft: 40px;" for="vueSelect" class="mt-1 ml-2 mr-1"> <strong>Semestre: </strong></label>
                                <v-select id="vueSelect" :options="arrayPeriod" label="period" v-model="period" class="ml-1"></v-select> 

                                <button style="marginLeft: 20px;" type="submit" @click="listStudent(1,search,criteria,period.period)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            
                            </div>
                                
                        </div>
                        
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre del Alumno</th>
                                <th>No. de Control</th>
                                <th>Grupo Extraescolar</th>
                                <th>Grupo Q8</th>
                                <th>Calificación</th>
                                <th>Opciones</th>
                                <th>Reportes</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in arrayStudent" :key="student.id">
                                <td ><a id="link" href="#" v-text="student.name + ' ' + student.lastName1 + ' ' + student.lastName2" @click="openModal('student', 'info', student)"></a></td>
                                <td v-text="student.email"></td>
                                <td v-if="student.group_name == null">Sin grupo</td><td v-else v-text="student.group_name"></td>
                                <td v-if="student.q8Name == null">Sin grupo</td><td v-else v-text="student.q8Name"></td>
                                <td v-if="student.grade == null">N/A</td><td v-else v-text="student.grade"></td>
                                <td style="width: 300px;">
                                    <div v-if="student.period == actualPeriod">
                                        <button v-if="student.condition" type="button" @click="openModal('student', 'update', student)" class="col-md-2 btn btn-warning">
                                            <i class="icon-pencil"></i>
                                        </button>
                                        <template>
                                            <button v-if="student.condition" type="button" class="col-md-2 btn btn-danger" @click="deactivateStudent(student.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="col-md-2 btn btn-info" @click="activateStudent(student.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                        <button v-if="student.condition" type="button" @click="openModal('student', 'changePsw', student)" class="col-md-2 btn fa fa-lock btn-info">
                                                
                                        </button>
                                        <button v-if="student.condition" type="button" @click="openModal('student', 'grade', student)" class="col-md-2 btn fa fa-graduation-cap btn-success">
                                                
                                        </button>
                                        <button type="button" @click="openModal('student', 'info', student)" class="col-md-2 btn fa fa-info btn-info">
                                            
                                        </button>
                                    </div>
                                                                        
                                </td>
                                <td>
                                    <a class="nav-link" target="_blank" :href="'/acta/'+student.id">Acta de Acreditación
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
                            <div v-if="actionType != 4 && actionType != 5">
                                <div class="form-group row">
                                    <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña actual</label>
                                    <div class="col-md-9">
                                        <input v-if="actionType==1 || actionType==2" type="text" v-model="name" class="form-control" placeholder="Nombre del alumno">
                                        <input v-else-if="actionType==3" type="password" v-model="currentPass" class="form-control" placeholder="Contraseña Actual">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="actionType==1 || actionType==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Primer Apellido</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="lastName1" class="form-control" placeholder="Primer Apellido">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="actionType==1 || actionType==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Segundo Apellido</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="lastName2" class="form-control" placeholder="Segundo Apellido">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Número de Control</label>
                                    <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña Nueva</label>
                                    <div class="col-md-9">
                                        <input v-if="actionType==1 || actionType==2" type="text" v-model="username" class="form-control" placeholder="No. de Control">
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
                                <div class="form-group row"  v-if="actionType==1 || actionType==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Carrera</label>
                                    <div class="col-md-9">
                                        <v-select id="vueSelect" :options="arrayCareer" label='career' v-model="career"></v-select>
                                    </div>
                                </div>
                                <div class="form-group row"  v-if="actionType==1 || actionType==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Grupo de Q8</label>
                                    <div class="col-md-9">
                                        <v-select id="vueSelect" :options="arrayQ8" label="nameQ8" v-model="q8"></v-select>
                                    </div>
                                </div>
                                <div class="form-group row"  v-if="actionType==1 || actionType==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Grupo Extraescolar</label>
                                    <div class="col-md-9">
                                        <v-select id="vueSelect" label="name" :options="arrayGroups" v-model="group"></v-select>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="actionType == 4">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Calificación del Alumno</label>
                                    <div class="col-md-9">
                                        <input type="number" maxlength="3" v-model="grade" class="form-control" placeholder="N/A">
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="actionType == 5">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="3" v-model="name" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">No. de Control</label>
                                    <div class="col-md-9">
                                        <input type="number" maxlength="3" v-model="username" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Grupo Q8</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="3" v-model="q8" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Grupo Extraescolar</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="3" v-model="group" class="form-control" placeholder="Sin Grupo" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Calificación</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="3" v-model="grade" class="form-control" placeholder="NA" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Semestre</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="3" v-model="period" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <!-- <div v-show="studentError" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in studentShowErrorMsg" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div> -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                                <button type="button" v-if="actionType==1" class="btn btn-primary" @click="registerStudent()">Guardar</button>
                                <button type="button" v-if="actionType==2" class="btn btn-primary" @click="updateStudent()">Actualizar</button>
                                <button type="button" v-if="actionType==3" class="btn btn-primary" @click="pVal()">Cambiar Contraseña</button>
                                <button type="button" v-if="actionType==4" class="btn btn-primary" @click="setGrade()">Ingresar Calficación</button>
                            </div>
                        </form>
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
                id:'',
                name: '',
                lastName1:'',
                lastName2:'',
                username: '',
                career: '',
                password: '',
                currentPass: '',
                period:'',
                actualPeriod:'',
                grade: 'NA',
                newPass: '',
                confirmPass:'',
                arrayStudent: [],
                arrayPeriod: [],
                arrayCareer:['ING. AERONÁUTICA','ING. BIOMÉDICA','ING. ELÉCTRICA','ING. ELECTRÓNICA','ING. INDUSTRIAL',
                'ING. MECÁNICA','ING. MECATRÓNICA','LIC. ADMINISTRACIÓN','ING. SISTEMA COMPUTACIONALES','ING. INFORMÁTICA','ING. GESTIÓN EMPRESARIAL'],
                id_q8: '',
                id_group: '',
                contition:'',

                //Modal Variables
                modal: 0,
                modalTitle: '',
                actionType: 0,
                group: '',
                q8:'',
                arrayGroups: [],
                arrayQ8: [],

                //Validate Student
                studentError: 0,
                studentShowErrorMsg: [],

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
            listGroups(){
                let me = this;
                axios.get('/admin/getGroups/').then(function (response){
                    //Executed Succesfully
                    me.arrayGroups = response.data.groups;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            listQ8(){
                let me = this;
                axios.get('/admin/getQ8Groups/').then(function (response){
                    //Executed Succesfully
                    me.arrayQ8 = response.data.q8Groups;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            listStudent(page,search,criteria,period=this.period){
                let me = this;
                var url = '/admin/alumno?page=' + page + '&search=' + search + '&criteria=' + criteria + '&period=' + period;
                //Categories table registries
                axios.get(url,{
                }).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    me.arrayStudent = respuesta.students.data;
                    me.arrayPeriod = respuesta.periods;
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
                me.listStudent(page, search, criteria, period.period);
            },
            registerStudent(){
                if (this.validateStudent()) {
                    toastr.warning(this.studentShowErrorMsg, 'Atención!', {timeOut: 5000});
                    return;
                }
                let me = this;
                let today = new Date();
                let sufix;
                if(today.getMonth <= 5) sufix = '1';
                else sufix = '1';
                this.period = today.getFullYear().toString() + sufix;
                axios.post('/admin/alumno/registrar', {
                    'username': this.username,
                    'password': this.password,
                    'paternal_surname': this.lastName1.toUpperCase(),
                    'maternal_surname': this.lastName2.toUpperCase(),
                    'name': this.name.toUpperCase(),
                    'period': this.period,
                    'career': this.career.toUpperCase(),
                    'grade': this.grade,
                    'id_group':this.group.id,
                    'id_q8': this.q8.id,
                }).then(function (response){
                    me.closeModal();
                    //Executed Succesfully
                    if (response.data.length != 0) {
                        Swal.fire({
                        type: 'error',
                        title: 'Registro duplicado',
                        text: response.data,
                        showConfirmButton: false,
                        timer: 2500
                        })
                        me.listStudent(1,'','name');
                    }
                    else{
                        Swal.fire({
                        type: 'success',
                        title: 'Éxito!',
                        text: 'Tu registro se ha creado correctamente!',
                        showConfirmButton: false,
                        timer: 2500
                        })
                        me.listStudent(1,'','name');
                    }
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
            updateStudent(){

                if (this.validateStudentUp()) {
                    toastr.warning(this.studentShowErrorMsg, 'Atención!', {timeOut: 5000});
                    return;
                }
                let me = this;
                axios.put('/admin/alumno/actualizar', {
                    'id': this.student_id,
                    'username': this.username,
                    'password': this.password,
                    'paternal_surname': this.lastName1.toUpperCase(),
                    'maternal_surname': this.lastName2.toUpperCase(),
                    'name': this.name.toUpperCase(),
                    'period': this.period,
                    'grade': this.grade,
                    'id_group':this.group.id,
                    'id_q8': this.q8.id,
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
                    me.listStudent(1,'','name');
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
                    me.listStudent(1,'','name');
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
            setGrade(){
                if (this.validateGrade()) {
                    return;
                }

                let me = this;
                if (!this.grade) this.grade = 'NA';
                axios.post('/admin/alumno/setGrade', {
                    'grade': this.grade,
                    'id': this.id
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
                    me.listStudent(1,'','name');
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
            validateStudent(){
                this.studentError=0;
                this.studentShowErrorMsg = [];
                var expAlpha = /^[a-zA-Z0-9]*$/i;
                var expNum = /^\d+$/i;
                var expLet = /^[ A-Za-z]+$/;
                //Nombre y apellido
                if(!this.name) this.studentShowErrorMsg.push('El Nombre del Alumno no puede estar vacío.');
                else if(!this.lastName1 && !this.lastName2) this.studentShowErrorMsg.push('Debe ingresar al menos un apellido.');
                else if(!this.name.match(expLet)) this.studentShowErrorMsg.push('Caracter inválido en Nombre.');
                else if((!this.lastName1.match(expLet) && this.lastName1) || (!this.lastName2.match(expLet) && this.lastName2)) this.studentShowErrorMsg.push('Caracter inválido en Apellidos.');
                else if(this.name.length > 30  || this.lastName1.length > 30 || this.lastName2.length > 30) this.studentShowErrorMsg.push('Nombre o apellidos demasiado grandes.');
                //No. de Control
                else if(!this.username) this.studentShowErrorMsg.push('El número de control no pueden estar vacíos.');
                else if(!this.username.match(expNum)) this.studentShowErrorMsg.push('Solo números en No. de Control.');
                else if(this.username.length != 8) this.studentShowErrorMsg.push('No. de Control debe de ser de 8 caractéres');
                //Contraseña
                else if(!this.password) this.studentShowErrorMsg.push('La contraseña no puede estar vacía.');
                else if(!this.password.match(expAlpha)) this.studentShowErrorMsg.push('Solo números y letras son permitidos en contraseña.');
                else if(this.password.length >190) this.studentShowErrorMsg.push('La contraseña es muy grande.');
                //Carrera
                else if(!this.career || this.career == "Seleccionar carrera") this.studentShowErrorMsg.push('Seleccione una carrera.');
                //Q8
                else if(!this.q8.id || this.career == "Seleccionar carrera") this.studentShowErrorMsg.push('Seleccione una grupo de Q8.');
                //Error
                if (this.studentShowErrorMsg.length) this.studentError = 1;
                
                return this.studentError;
            },
            validateGrade(){
                this.studentError=0;
                this.studentShowErrorMsg = [];
                if(this.grade > 100) this.studentShowErrorMsg.push('La calificación no puede ser mayor a 100.');
                if (this.studentShowErrorMsg.length) this.studentError = 1;
                
                return this.studentError;
            },
            validateStudentUp(){
                this.studentError=0;
                this.studentShowErrorMsg = [];
                var expAlpha = /((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i;
                var expNum = /^\d+$/i;
                var expLet = /^[ A-Za-z]+$/;

                 //Nombre y apellido
                if(!this.name) this.studentShowErrorMsg.push('El Nombre del Alumno no puede estar vacío.');
                else if(!this.lastName1 && !this.lastName2) this.studentShowErrorMsg.push('Debe ingresar al menos un apellido.');
                else if(!this.name.match(expLet)) this.studentShowErrorMsg.push('Caracter inválido en Nombre.');
                else if((!this.lastName1.match(expLet) && this.lastName1) || (!this.lastName2.match(expLet) && this.lastName2)) this.studentShowErrorMsg.push('Caracter inválido en Apellidos.');
                else if(this.name.length > 30  || this.lastName1.length > 30 || this.lastName2.length > 30) this.studentShowErrorMsg.push('Nombre o apellidos demasiado grandes.');
                //No. de Control
                else if(!this.username) this.studentShowErrorMsg.push('El número de control no pueden estar vacíos.');
                else if(!this.username.match(expNum)) this.studentShowErrorMsg.push('Solo números en No. de Control.');
                else if(this.username.length != 8) this.studentShowErrorMsg.push('No. de Control debe de ser de 8 caractéres');
        
                //Carrera
                else if(!this.career || this.career == "Seleccionar carrera") this.studentShowErrorMsg.push('Seleccione una carrera.');
                //Q8
                else if(!this.q8.id || this.career == "Seleccionar carrera") this.studentShowErrorMsg.push('Seleccione una grupo de Q8.');

                if (this.studentShowErrorMsg.length) this.studentError = 1;
                
                return this.studentError;
            },
            closeModal(){
                this.modal = 0;
                this.modalTitle = '';
                this.name = '';
                this.username = '';
                this.password = '';
                this.studentError = 0;
            },
            openModal(model, action, data = []){
                switch (model) {
                    case "student":
                    {
                        switch (action) {
                            case 'register':
                            {
                                //Show Modal
                                this.modal = 1;
                                this.modalTitle = 'Registrar Alumno';
                                this.name = '';
                                this.lastName1 = '';
                                this.lastName2 = '';
                                this.username = '';
                                this.password = '';
                                this.q8 = 'Sin asignar';
                                this.group = 'Sin asignar';
                                this.career = 'Seleccionar carrera';
                                this.actionType = 1;
                                break;
                            }
                            case 'update':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Actualizar Alumno';
                                this.student_id = data['id'];
                                this.name = data['name'];
                                this.lastName1 = data['lastName1'];
                                this.lastName2 = data['lastName2'];
                                this.username = data['email'];
                                this.password = data['password'];
                                this.period = data['period'];
                                this.career = data['career'];
                                this.group = {
                                    'id': data['group_id'],
                                    'name': data['group_name'],
                                };
                                this.q8 = {
                                    'id': data['id_q8'],
                                    'nameQ8': data['q8Name'],
                                    'period': data['period']
                                };
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
                                this.password = data['password'];
                                this.actionType = 3;
                                this.id_user = data['id_user']
                                break;
                            }
                            case 'grade':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Cambiar Calficación';
                                this.grade = data['grade'];
                                this.id = data['id'];
                                this.actionType = 4;
                                this.id_user = data['id_user']
                                break;
                            }
                            case 'info':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Información del Alumno';
                                this.grade = data['grade'];
                                this.id = data['id'];
                                this.actionType = 5;
                                this.id_user = data['id_user']
                                this.name = data['name'] + ' ' + data['lastName1'] + ' ' + data['lastName2']
                                this.username = data['email'];
                                this.period = data['period'];
                                this.group = data['group_name']; 
                                this.id_group = data['group_id'];
                                this.q8 = data['q8Name'];
                                this.id_q8 = data['id_q8'];
                                this.contition = data['teacher_condition'];
                                break;
                            }
                        }
                    }
                }
            },
            deactivateStudent(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Desactivar Alumno',
                text: "¿Esta seguro que quiere desactivar este alumno?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.post('/admin/alumno/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listStudent(1,'','name');

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
            activateStudent(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })
                swalWithBootstrapButtons.fire({
                title: 'Activar Alumno',
                text: "¿Está seguro que quiere activar de nuevo este alumno?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.post('/admin/alumno/toggle', {
                        'id': id,
                    }).then(function (response){
                        //Executed Succesfully
                        me.listStudent(1,'','name');
                        swalWithBootstrapButtons.fire(
                        'Activado Satisfactoriamente!',
                        'El alumno ha sido activado satisfactoriamente.',
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

            exportPDF(id){
                let me = this;
                var url = '/admin/alumno/certificado/{id}' + id;
                axios.get(url, {}).then(function (response){
                    console.log(response);
                    if (response.data == false) {
                        toastr.error('El alumno seleccionado no ha completado el crédito de Extraescolares', 'Error');
                    }
                    //Executed Succesfully
                    // Swal.fire(
                    // 'Hiciste click!',
                    // 'Funcionalidad en desarrollo',
                    // 'success'
                    // );
                    me.closeModal();
                    me.listQ8(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                });
            },

        },
        mounted() {
            
            let today = new Date();
            let sufix;
            if(today.getMonth <= 5) sufix = '1';
            else sufix = '1';
            this.period = today.getFullYear().toString() + sufix;
            this.actualPeriod = today.getFullYear().toString() + sufix;
            this.listStudent(1,this.search,this.criteria, this.period);
            this.listGroups();
            this.listQ8();
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
    #link { 
        color: rgb(0, 0, 0); 
    } /* CSS link color */
</style>

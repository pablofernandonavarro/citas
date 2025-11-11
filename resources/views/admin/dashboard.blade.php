<x-admin-layout>

    @role(['Admin','Recepcionista'])
        
        @include('admin.dashboard.admin')
    @endrole
      @role('Doctor')
        
        @include('admin.dashboard.doctor')
    @endrole
     @role('Paciente')
        
        @include('admin.dashboard.patient')
    @endrole


</x-admin-layout>

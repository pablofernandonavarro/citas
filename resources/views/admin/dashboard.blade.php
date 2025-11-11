<x-admin-layout>

    @role(['Admin','Recepcionista'])
        
        @include('admin.dashboard.admin')
    @endrole
      @role('Doctor')
        
        @include('admin.dashboard.doctor')
    @endrole


</x-admin-layout>

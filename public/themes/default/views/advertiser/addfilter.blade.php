<a class="btn btn-info btn-sm" id="sync_legacy"><i class="fa fa-refresh"></i> Sync with Legacy Data</a>
<span class="syncing" style="display:none;">Processing...</span>

<style type="text/css">

.modal.large {
    width: 80%; /* respsonsive width */
    margin-left:-40%; /* width/2) */
}

.modal.large .modal-body{
    max-height: 800px;
    height: 500px;
    overflow: auto;
}

button#label_refresh{
    margin-top: 27px;
}

button#label_default{
    margin-top: 28px;
}

</style>

<script type="text/javascript">
    $(document).ready(function(){

        $('#sync_legacy').on('click',function(e){
            $('.syncing').show();
            $.post('{{ URL::to( $sync_url )}}',
                {},
                function(data){
                    if(data.result == 'OK'){
                        alert('Legacy data synced. ' + data.count + ' records updated' );
                        oTable.draw();
                    }else{
                        alert('Sync failed, nothing is changed');
                    }
                    $('.syncing').hide();
                }
                ,'json');
                e.preventDefault();
        });

    });
</script>
<div class="container">
    <!-- Trigger the modal with a button -->
{{--<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>--}}

<!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">輸入賣出金額</h4>
                </div>
                <form class="form-horizontal" action="{{route('warehouse.sold')}}" method="post" name="modal-form">
                    {{csrf_field()}}
                    <table class="table table-striped table-bordered table-responsive table-h3">
                        <thead>
                        <tr>
                            <td>名稱</td>
                            <td>買入金額</td>
                            <td>買入日期</td>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="warehouse,index in soldWarehouses">
                            <td>@{{warehouse.name}}</td>
                            <td>@{{warehouse.buy_price}}</td>
                            <td>@{{warehouse.buy_date}}</td>
                            <input type="hidden" name="soldModalWarehouseIDs[]" :value="warehouse.id">
                        </tr>
                        </tbody>
                    </table>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="soldPrice" class="col-sm-4 control-label">賣出金額：</label>
                            <div class="col-sm-4">
                                <input type="text" pattern="[0-9]+([\.][0-9]{0,2})?"
                                       title="輸入小數點兩位以內數值" class="form-control" name="soldPrice"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-default" value="確定">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
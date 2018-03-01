<div id="new" class="tab-pane fade">
    <div class="container">
        <form class="form-horizontal" method="post" action="{{route('warehouse.add')}}">
            {{csrf_field()}}
            <div class="form-group">
                <label for="buyPrice" class="col-sm-2 control-label">價格：</label>
                <div class="col-sm-2">
                    <input type="number"
                           class="form-control" name="buyPrice"
                           id="wave-point"/>
                </div>
            </div>
            <div class="form-group">
                <label for="sheets" class="col-sm-2 control-label">張數：</label>
                <div class="col-sm-2">
                    <input type="number" class="form-control" name="sheets" id="lot"
                           value="1"/>
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-sm-2 control-label">類型：</label>
                <div class="col-sm-9" name="type">
                    <select name="type" v-model="buyType">
                        @foreach(\App\Enums\WarehouseType::toArray() as $key=>$value)
                            @if($value != \App\Enums\WarehouseType::全部)
                                <option value="{{$value}}">{{$key}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-sm-2 control-label">買入日期：</label>
                <div class="col-sm-9 input-append date form_date" data-date="" data-date-format="dd MM yyyy"
                     data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input size="16" type="text" value="" readonly name="buyDate">
                    <span class="add-on"><i class="icon-remove"></i></span>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <input type="hidden" id="dtp_input2" value=""/><br/>
            </div>
            <div class="form-group">
                <label for="money" class="col-sm-2 control-label"></label>
                <div class="col-sm-9">
                    <input type="button" onclick="if(confirm('確認新增？')){this.form.submit()}" class="btn btn-success"
                           value="新增"/>
                </div>
            </div>
        </form>
    </div>
</div>
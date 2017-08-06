{include file='ping/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            新建测试
            <small>New Test</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div id="msg-ing" class="alert alert-success" style="display: none">
                    <h4 id="msg-h4">
                        正在创建测试，请稍候...
                    </h4>
                </div>
            </div>
        </div>
        <!-- START PROGRESS BARS -->
        <div class="row">
            {foreach $nodes as $node}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{$node->name}</h3>

                        <p id="node{$node->id}">{$node->server}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-speedometer"></i>
                    </div>
                    <a href="javascript: launch({$node->id})" class="small-box-footer"> 新建测试 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            {/foreach}
        </div>
        <!-- /.row --><!-- END PROGRESS BARS -->
    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    function launch (id) {
        $("#msg-ing").css('display', 'block');
        $(".small-box-footer").attr('href', '#!');
        $.ajax({
            url: "/ping/token",
            method: "GET",
            success: function (msg) {
                var dataObj = eval("(" + msg + ")");
                if (!dataObj['ret'])
                    return;
                var token = dataObj['data']['token'];
                $.ajax({
                    url: "http://network.cool2645.com:801/api/jobs",
                    method: "POST",
                    data: {
                        config: "mu_api_v2_token",
                        website: "{$config['baseUrl']}" + "/api",
                        node: $("#node" + id.toString()).html(),
                        token: token,
                        docker: "cool2645/shadowsocks-pip"
                    },
                    success: function (msg) {
                        var dataObj = eval("(" + msg + ")");
                        if (!dataObj) {
                            $("#msg-h4").html("与服务器通信失败。");
                            $("#msg-ing").removeClass("alert-success");
                            $("#msg-ing").addClass("alert-danger");
                        }
                        else if (!dataObj.result) {
                            $("#msg-h4").html("发生错误：" + dataObj.msg);
                            $("#msg-ing").removeClass("alert-success");
                            $("#msg-ing").addClass("alert-danger");
                        }
                        else {
                            window.location.href = "/status";
                        }
                    },
                    error: function (xhr) {
                        $("#msg-h4").html("与服务器通信错误。");
                        $("#msg-ing").removeClass("alert-success");
                        $("#msg-ing").addClass("alert-danger");
                    }
                })
            }
        });
    };
</script>

{include file='ping/footer.tpl'}
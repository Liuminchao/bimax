<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header p-2 " style="text-align: center;color: #fff;background-color: #3ABDC7;">
                Pending
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <table style="width: 100%">
                        <?php
                            $show_list = ProgramBlockChart::ShowTask($pbu_id,$project_id,$stage_id);
                            foreach($show_list['pending'] as $item => $value){
                                foreach ($value as $task_name => $date){
                                    echo "<tr><td>$task_name</td><td align='right'>$date</td></tr>";
                                }
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header p-2 nav-link" style="text-align: center;color: #fff;background-color: #3ABDC7;">
                Completed
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <table style="width: 100%">
                        <?php
                        $show_list = ProgramBlockChart::ShowTask($pbu_id,$project_id,$stage_id);
                        foreach($show_list['completed'] as $item => $value){
                            foreach ($value as $task_name => $date){
                                echo "<tr><td>$task_name</td><td align='right'>$date</td></tr>";
                            }
                        }
                        ?>
                        <tr>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

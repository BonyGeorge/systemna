<?php
$pageTitle = "SYSTEMNA | Requested Letters";
include "../template/header.php";
?>

<br>
<div style="text-align: center;">
    <h1 style="font-family: sans-serif;">Your Requests</h1>
</div>
<br><br>
<table id='Display'>
    <tr id='must'>
        <th>#</th>
        <th>Request ID</th>
        <th>Status</th>
        <th>Applied for Letter</th>
        <th>Priority</th>
        <th>Salary</th>
        <th>Date applied for</th>
        <th colspan="3">Actions</th>
    </tr>

    <?php

    $sql="  SELECT *
    FROM requests INNER join requests_types
    on requests.type_name=requests_types.Name where emp_id='".$_SESSION['id']."'";
    try
    {

        $DB->query($sql);
        $DB->execute();
        $y=0;
        if($DB->numRows()>0)
        {
            for($i=0;$i<$DB->numRows();$i++)
            {
                $x=$DB->getdata();
                $y++;
                $id=$x[$i]->Request_id;
                $emp_id=$_SESSION['id'];
                $priority=$x[$i]->priority;
                $salary=$x[$i]->salary;
                $date=$x[$i]->date;
                $type_name=$x[$i]->type_name;


                $Boolsalray = "Without Salary";
                $BoolPriority = "Urgent";

                if($salary == 1)
                {
                    $Boolsalray ="With Salary";
                }
                else $Boolsalray ="Without Salary";

                if($priority == 1)
                {
                    $BoolPriority ="Urgent";
                }
                else $BoolPriority="Normal";
                echo  "<tr>";
                echo "<td>{$y}</td>";

                echo "<td>{$id}</td>";
                //                echo "<td>$emp_id</td>";


                if($x[$i]->Status==1){
                    echo "<td style='color:green; font-weight:bold;'>Accepted</td>";}
                else if($x[$i]->Status==0){
                    echo "<td style='color:red; font-weight:bold;' >Rejected</td>";}
                else{
                    echo "<td style='color:#be800d; font-weight:bold;' >Pending</td>";
                }
                echo "<td>{$type_name}</td>";
                echo "<td>{$BoolPriority}</td>";
                echo "<td>{$Boolsalray}</td>";

                echo "<td>$date</td>";
                if($x[$i]->Status==1){
    ?>

    <td colspan="3"><button id='<?php echo $x[$i]->type_name;?>' onclick="showdata(this.id,'<?php echo $x[$i]->salary;?>' ,'<?php echo $x[$i]->date;?>','<?php echo $id; ?>')" class="btn btn-info btn-sml" data-toggle="modal" data-target='#exampleModalLong'>View Letter</button></td>


    <?php } else if ($x[$i]->Status==0){
    ?>

    <td colspan="4"><p style="color:red; font-weight:bold;">Rejected</p></td>

    <?php }


                else   {
    ?>

    <td colspan="2"><a id="id=<?php echo $x[$i]->Request_id ;?> " href="../operations/deleterequest.php" class='deleteConfirmation EditBtn'>Delete</a></td>

    <td colspan="2"><a href="../pages/editLetter.php?id=<?php echo $x[$i]->Request_id ;?> " class='EditBtn1'>Edit</a></td>


    <?php
                    echo "</tr>";
                }
            }

        }
        else {
            echo"<tr><td colspan=9>You have no requests for now ! </td></tr>";
        }
    }
    catch(Exception $e)
    {
        $_SESSION['error'] = 'error in sql';
        error_log("Error while user trying to view letter request");
        echo "<br><div class='alert alert-danger' style='text-align: center;'>ERROR! Please try again later</div>";
    }

    ?>
</table>





<div class="modal fade bd-example-modal-xl" tabindex="-1" id="exampleModalLong" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Letter View</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="body">
                <div>
                    <p>Sorry something went wrong!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="Export2Doc('body','SYSTEMNA HR Letter')">Download</button>
                <button type="button" class="btn btn-primary" id="sendletteronmail">Get on mail</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    function showdata(id,salary,date,request_id){
        jQuery.ajax({
            url: "view_letter.php",
            data:'id='+id+'&salary='+salary+'&date='+date+'&request_id='+request_id,
            type:"POST",

            success:function(data)
            {
                $("#body").html(data);
            }
        });
    }

    /* Function to send mail with letter to user */
    $("#sendletteronmail").on("click", function () {
        $("#exampleModalLong").modal("hide");
        $.ajax({
            type: "POST",
            url: "../operations/massmsging.php",
            data: "data=" + $("#body").html() + "&type=sendlettermail",
            success: function (html) {
                loading(false);
                if (html.includes("true")) {
                    html = "Success";
                }
                else {
                    html = "Failed";
                }
                $(".popup-notification h2").text(html);
                $(".popup-content").html('Mail sent successfully');
                $(".modalPopup").css("display", "block");
            },
            beforeSend: function () {
                loading(true);
            }
        });
    });

    function loading(status) {
        if (status == true) {
            $(".loading").removeClass("hidden");
            $(".content").addClass("hidden");
        }
        else if (status == false) {
            $(".loading").addClass("hidden");
            $(".content").removeClass("hidden");
        }
    }

    function Export2Doc(element, filename = ''){

        var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
        var postHtml = "</body></html>";
        var html = preHtml+document.getElementById(element).innerHTML+postHtml;

        var blob = new Blob(['\ufeff', html], {
            type: 'application/msword'
        });

        // Specify link url
        var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);
        // Specify file name
        filename = filename?filename+'.doc':'document.doc';

        // Create download link element
        var downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob ){
            navigator.msSaveOrOpenBlob(blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = url;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }

        document.body.removeChild(downloadLink);
    }
    /*
    function pdf(){
        var doc = new jsPDF();
        var elementHTML = document.getElementById('body').innerHTML;
        var specialElementHandlers = {
            '#elementH': function (element, renderer) {
                return true;
            }
        };
        doc.fromHTML(elementHTML, 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });

        // Save the PDF
        doc.save('sample-document.pdf');
    }*/
</script>

<?php include "../template/footer.php"; ?>

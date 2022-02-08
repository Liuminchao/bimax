<!-- bootstrap 3.0.2 -->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">

</style>
<link rel="stylesheet" href="htmlapi/pdfjs/web/viewer.css">


<!-- This snippet is used in production (included from viewer.html) -->
<link rel="resource" type="application/l10n" href="htmlapi/pdfjs/web/locale/locale.properties">
<script src="htmlapi/pdfjs/build/pdf.js"></script>


<script src="htmlapi/pdfjs/web/viewer.js"></script>


<div id="outerContainer" style="width: 50%;float: left">

    <div id="sidebarContainer">
        <div id="toolbarSidebar">
            <div class="splitToolbarButton toggled">
                <button id="viewThumbnail" class="toolbarButton toggled" title="Show Thumbnails" tabindex="2" data-l10n-id="thumbs">
                    <span data-l10n-id="thumbs_label">Thumbnails</span>
                </button>
                <button id="viewOutline" class="toolbarButton" title="Show Document Outline (double-click to expand/collapse all items)" tabindex="3" data-l10n-id="document_outline">
                    <span data-l10n-id="document_outline_label">Document Outline</span>
                </button>
                <button id="viewAttachments" class="toolbarButton" title="Show Attachments" tabindex="4" data-l10n-id="attachments">
                    <span data-l10n-id="attachments_label">Attachments</span>
                </button>
            </div>
        </div>
        <div id="sidebarContent">
            <div id="thumbnailView">
            </div>
            <div id="outlineView" class="hidden">
            </div>
            <div id="attachmentsView" class="hidden">
            </div>
        </div>
        <div id="sidebarResizer" class="hidden"></div>
    </div>  <!-- sidebarContainer -->

    <div id="mainContainer">
        <div class="findbar hidden doorHanger" id="findbar">
            <div id="findbarInputContainer">
                <input id="findInput" class="toolbarField" title="Find" placeholder="Find in document…" tabindex="91" data-l10n-id="find_input">
                <div class="splitToolbarButton">
                    <button id="findPrevious" class="toolbarButton findPrevious" title="Find the previous occurrence of the phrase" tabindex="92" data-l10n-id="find_previous">
                        <span data-l10n-id="find_previous_label">Previous</span>
                    </button>
                    <div class="splitToolbarButtonSeparator"></div>
                    <button id="findNext" class="toolbarButton findNext" title="Find the next occurrence of the phrase" tabindex="93" data-l10n-id="find_next">
                        <span data-l10n-id="find_next_label">Next</span>
                    </button>
                </div>
            </div>

            <div id="findbarOptionsOneContainer">
                <input type="checkbox" id="findHighlightAll" class="toolbarField" tabindex="94">
                <label for="findHighlightAll" class="toolbarLabel" data-l10n-id="find_highlight">Highlight all</label>
                <input type="checkbox" id="findMatchCase" class="toolbarField" tabindex="95">
                <label for="findMatchCase" class="toolbarLabel" data-l10n-id="find_match_case_label">Match case</label>
            </div>
            <div id="findbarOptionsTwoContainer">
                <input type="checkbox" id="findEntireWord" class="toolbarField" tabindex="96">
                <label for="findEntireWord" class="toolbarLabel" data-l10n-id="find_entire_word_label">Whole words</label>
                <span id="findResultsCount" class="toolbarLabel hidden"></span>
            </div>

            <div id="findbarMessageContainer">
                <span id="findMsg" class="toolbarLabel"></span>
            </div>
        </div>  <!-- findbar -->

        <div id="secondaryToolbar" class="secondaryToolbar hidden doorHangerRight">
            <div id="secondaryToolbarButtonContainer">
                <button id="secondaryPresentationMode" class="secondaryToolbarButton presentationMode visibleLargeView" title="Switch to Presentation Mode" tabindex="51" data-l10n-id="presentation_mode">
                    <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
                </button>

                <button id="secondaryOpenFile" class="secondaryToolbarButton openFile visibleLargeView" title="Open File" tabindex="52" data-l10n-id="open_file">
                    <span data-l10n-id="open_file_label">Open</span>
                </button>

                <button id="secondaryPrint" class="secondaryToolbarButton print visibleMediumView" title="Print" tabindex="53" data-l10n-id="print">
                    <span data-l10n-id="print_label">Print</span>
                </button>

                <button id="secondaryDownload" class="secondaryToolbarButton download visibleMediumView" title="Download" tabindex="54" data-l10n-id="download">
                    <span data-l10n-id="download_label">Download</span>
                </button>

                <a href="#" id="secondaryViewBookmark" class="secondaryToolbarButton bookmark visibleSmallView" title="Current view (copy or open in new window)" tabindex="55" data-l10n-id="bookmark">
                    <span data-l10n-id="bookmark_label">Current View</span>
                </a>

                <div class="horizontalToolbarSeparator visibleLargeView"></div>

                <button id="firstPage" class="secondaryToolbarButton firstPage" title="Go to First Page" tabindex="56" data-l10n-id="first_page">
                    <span data-l10n-id="first_page_label">Go to First Page</span>
                </button>
                <button id="lastPage" class="secondaryToolbarButton lastPage" title="Go to Last Page" tabindex="57" data-l10n-id="last_page">
                    <span data-l10n-id="last_page_label">Go to Last Page</span>
                </button>

                <div class="horizontalToolbarSeparator"></div>

                <button id="pageRotateCw" class="secondaryToolbarButton rotateCw" title="Rotate Clockwise" tabindex="58" data-l10n-id="page_rotate_cw">
                    <span data-l10n-id="page_rotate_cw_label">Rotate Clockwise</span>
                </button>
                <button id="pageRotateCcw" class="secondaryToolbarButton rotateCcw" title="Rotate Counterclockwise" tabindex="59" data-l10n-id="page_rotate_ccw">
                    <span data-l10n-id="page_rotate_ccw_label">Rotate Counterclockwise</span>
                </button>

                <div class="horizontalToolbarSeparator"></div>

                <button id="cursorSelectTool" class="secondaryToolbarButton selectTool toggled" title="Enable Text Selection Tool" tabindex="60" data-l10n-id="cursor_text_select_tool">
                    <span data-l10n-id="cursor_text_select_tool_label">Text Selection Tool</span>
                </button>
                <button id="cursorHandTool" class="secondaryToolbarButton handTool" title="Enable Hand Tool" tabindex="61" data-l10n-id="cursor_hand_tool">
                    <span data-l10n-id="cursor_hand_tool_label">Hand Tool</span>
                </button>

                <div class="horizontalToolbarSeparator"></div>

                <button id="scrollVertical" class="secondaryToolbarButton scrollModeButtons scrollVertical toggled" title="Use Vertical Scrolling" tabindex="62" data-l10n-id="scroll_vertical">
                    <span data-l10n-id="scroll_vertical_label">Vertical Scrolling</span>
                </button>
                <button id="scrollHorizontal" class="secondaryToolbarButton scrollModeButtons scrollHorizontal" title="Use Horizontal Scrolling" tabindex="63" data-l10n-id="scroll_horizontal">
                    <span data-l10n-id="scroll_horizontal_label">Horizontal Scrolling</span>
                </button>
                <button id="scrollWrapped" class="secondaryToolbarButton scrollModeButtons scrollWrapped" title="Use Wrapped Scrolling" tabindex="64" data-l10n-id="scroll_wrapped">
                    <span data-l10n-id="scroll_wrapped_label">Wrapped Scrolling</span>
                </button>

                <div class="horizontalToolbarSeparator scrollModeButtons"></div>

                <button id="spreadNone" class="secondaryToolbarButton spreadModeButtons spreadNone toggled" title="Do not join page spreads" tabindex="65" data-l10n-id="spread_none">
                    <span data-l10n-id="spread_none_label">No Spreads</span>
                </button>
                <button id="spreadOdd" class="secondaryToolbarButton spreadModeButtons spreadOdd" title="Join page spreads starting with odd-numbered pages" tabindex="66" data-l10n-id="spread_odd">
                    <span data-l10n-id="spread_odd_label">Odd Spreads</span>
                </button>
                <button id="spreadEven" class="secondaryToolbarButton spreadModeButtons spreadEven" title="Join page spreads starting with even-numbered pages" tabindex="67" data-l10n-id="spread_even">
                    <span data-l10n-id="spread_even_label">Even Spreads</span>
                </button>

                <div class="horizontalToolbarSeparator spreadModeButtons"></div>

                <button id="documentProperties" class="secondaryToolbarButton documentProperties" title="Document Properties…" tabindex="68" data-l10n-id="document_properties">
                    <span data-l10n-id="document_properties_label">Document Properties…</span>
                </button>
            </div>
        </div>  <!-- secondaryToolbar -->

        <div class="toolbar">
            <div id="toolbarContainer">
                <div id="toolbarViewer">
                    <div id="toolbarViewerLeft">
                        <button id="sidebarToggle" class="toolbarButton" title="Toggle Sidebar" tabindex="11" data-l10n-id="toggle_sidebar">
                            <span data-l10n-id="toggle_sidebar_label">Toggle Sidebar</span>
                        </button>
                        <div class="toolbarButtonSpacer"></div>
                        <button id="viewFind" class="toolbarButton" title="Find in Document" tabindex="12" data-l10n-id="findbar">
                            <span data-l10n-id="findbar_label">Find</span>
                        </button>
                        <div class="splitToolbarButton hiddenSmallView">
                            <button class="toolbarButton pageUp" title="Previous Page" id="previous" tabindex="13" data-l10n-id="previous">
                                <span data-l10n-id="previous_label">Previous</span>
                            </button>
                            <div class="splitToolbarButtonSeparator"></div>
                            <button class="toolbarButton pageDown" title="Next Page" id="next" tabindex="14" data-l10n-id="next">
                                <span data-l10n-id="next_label">Next</span>
                            </button>
                        </div>
                        <input type="number" id="pageNumber" class="toolbarField pageNumber" title="Page" value="1" size="4" min="1" tabindex="15" data-l10n-id="page">
                        <span id="numPages" class="toolbarLabel"></span>
                    </div>
                    <div id="toolbarViewerRight">
                        <button id="presentationMode" class="toolbarButton presentationMode hiddenLargeView" title="Switch to Presentation Mode" tabindex="31" data-l10n-id="presentation_mode">
                            <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
                        </button>

                        <button id="openFile" class="toolbarButton openFile hiddenLargeView" title="Open File" tabindex="32" data-l10n-id="open_file">
                            <span data-l10n-id="open_file_label">Open</span>
                        </button>

                        <button id="print" class="toolbarButton print hiddenMediumView" title="Print" tabindex="33" data-l10n-id="print">
                            <span data-l10n-id="print_label">Print</span>
                        </button>

                        <button id="download" class="toolbarButton download hiddenMediumView" title="Download" tabindex="34" data-l10n-id="download">
                            <span data-l10n-id="download_label">Download</span>
                        </button>
                        <a href="#" id="viewBookmark" class="toolbarButton bookmark hiddenSmallView" title="Current view (copy or open in new window)" tabindex="35" data-l10n-id="bookmark">
                            <span data-l10n-id="bookmark_label">Current View</span>
                        </a>

                        <div class="verticalToolbarSeparator hiddenSmallView"></div>

                        <button id="secondaryToolbarToggle" class="toolbarButton" title="Tools" tabindex="36" data-l10n-id="tools">
                            <span data-l10n-id="tools_label">Tools</span>
                        </button>
                    </div>
                    <div id="toolbarViewerMiddle">
                        <div class="splitToolbarButton">
                            <button id="zoomOut" class="toolbarButton zoomOut" title="Zoom Out" tabindex="21" data-l10n-id="zoom_out">
                                <span data-l10n-id="zoom_out_label">Zoom Out</span>
                            </button>
                            <div class="splitToolbarButtonSeparator"></div>
                            <button id="zoomIn" class="toolbarButton zoomIn" title="Zoom In" tabindex="22" data-l10n-id="zoom_in">
                                <span data-l10n-id="zoom_in_label">Zoom In</span>
                            </button>
                        </div>
                        <span id="scaleSelectContainer" class="dropdownToolbarButton">
                  <select id="scaleSelect" title="Zoom" tabindex="23" data-l10n-id="zoom">
                    <option id="pageAutoOption" title="" value="auto" selected="selected" data-l10n-id="page_scale_auto">Automatic Zoom</option>
                    <option id="pageActualOption" title="" value="page-actual" data-l10n-id="page_scale_actual">Actual Size</option>
                    <option id="pageFitOption" title="" value="page-fit" data-l10n-id="page_scale_fit">Page Fit</option>
                    <option id="pageWidthOption" title="" value="page-width" data-l10n-id="page_scale_width">Page Width</option>
                    <option id="customScaleOption" title="" value="custom" disabled="disabled" hidden="true"></option>
                    <option title="" value="0.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 50 }'>50%</option>
                    <option title="" value="0.75" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 75 }'>75%</option>
                    <option title="" value="1" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 100 }'>100%</option>
                    <option title="" value="1.25" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 125 }'>125%</option>
                    <option title="" value="1.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 150 }'>150%</option>
                    <option title="" value="2" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 200 }'>200%</option>
                    <option title="" value="3" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 300 }'>300%</option>
                    <option title="" value="4" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 400 }'>400%</option>
                  </select>
                </span>
                    </div>
                </div>
                <div id="loadingBar">
                    <div class="progress">
                        <div class="glimmer">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <menu type="context" id="viewerContextMenu">
            <menuitem id="contextFirstPage" label="First Page"
                      data-l10n-id="first_page"></menuitem>
            <menuitem id="contextLastPage" label="Last Page"
                      data-l10n-id="last_page"></menuitem>
            <menuitem id="contextPageRotateCw" label="Rotate Clockwise"
                      data-l10n-id="page_rotate_cw"></menuitem>
            <menuitem id="contextPageRotateCcw" label="Rotate Counter-Clockwise"
                      data-l10n-id="page_rotate_ccw"></menuitem>
        </menu>

        <div id="viewerContainer" tabindex="0">
            <div id="viewer" class="pdfViewer"></div>
        </div>

        <div id="errorWrapper" hidden='true'>
            <div id="errorMessageLeft">
                <span id="errorMessage"></span>
                <button id="errorShowMore" data-l10n-id="error_more_info">
                    More Information
                </button>
                <button id="errorShowLess" data-l10n-id="error_less_info" hidden='true'>
                    Less Information
                </button>
            </div>
            <div id="errorMessageRight">
                <button id="errorClose" data-l10n-id="error_close">
                    Close
                </button>
            </div>
            <div class="clearBoth"></div>
            <textarea id="errorMoreInfo" hidden='true' readonly="readonly"></textarea>
        </div>
    </div> <!-- mainContainer -->

    <div id="overlayContainer" class="hidden">
        <div id="passwordOverlay" class="container hidden">
            <div class="dialog">
                <div class="row">
                    <p id="passwordText" data-l10n-id="password_label">Enter the password to open this PDF file:</p>
                </div>
                <div class="row">
                    <input type="password" id="password" class="toolbarField">
                </div>
                <div class="buttonRow">
                    <button id="passwordCancel" class="overlayButton"><span data-l10n-id="password_cancel">Cancel</span></button>
                    <button id="passwordSubmit" class="overlayButton"><span data-l10n-id="password_ok">OK</span></button>
                </div>
            </div>
        </div>
        <div id="documentPropertiesOverlay" class="container hidden">
            <div class="dialog">
                <div class="row">
                    <span data-l10n-id="document_properties_file_name">File name:</span> <p id="fileNameField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_file_size">File size:</span> <p id="fileSizeField">-</p>
                </div>
                <div class="separator"></div>
                <div class="row">
                    <span data-l10n-id="document_properties_title">Title:</span> <p id="titleField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_author">Author:</span> <p id="authorField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_subject">Subject:</span> <p id="subjectField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_keywords">Keywords:</span> <p id="keywordsField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_creation_date">Creation Date:</span> <p id="creationDateField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_modification_date">Modification Date:</span> <p id="modificationDateField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_creator">Creator:</span> <p id="creatorField">-</p>
                </div>
                <div class="separator"></div>
                <div class="row">
                    <span data-l10n-id="document_properties_producer">PDF Producer:</span> <p id="producerField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_version">PDF Version:</span> <p id="versionField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_page_count">Page Count:</span> <p id="pageCountField">-</p>
                </div>
                <div class="row">
                    <span data-l10n-id="document_properties_page_size">Page Size:</span> <p id="pageSizeField">-</p>
                </div>
                <div class="separator"></div>
                <div class="row">
                    <span data-l10n-id="document_properties_linearized">Fast Web View:</span> <p id="linearizedField">-</p>
                </div>
                <div class="buttonRow">
                    <button id="documentPropertiesClose" class="overlayButton"><span data-l10n-id="document_properties_close">Close</span></button>
                </div>
            </div>
        </div>
        <div id="printServiceOverlay" class="container hidden">
            <div class="dialog">
                <div class="row">
                    <span data-l10n-id="print_progress_message">Preparing document for printing…</span>
                </div>
                <div class="row">
                    <progress value="0" max="100"></progress>
                    <span data-l10n-id="print_progress_percent" data-l10n-args='{ "progress": 0 }' class="relative-progress">0%</span>
                </div>
                <div class="buttonRow">
                    <button id="printCancel" class="overlayButton"><span data-l10n-id="print_progress_close">Cancel</span></button>
                </div>
            </div>
        </div>
    </div>  <!-- overlayContainer -->

</div> <!-- outerContainer -->
<div id="cav" style="width: 50%;height:100%;float: left;background:whitesmoke;" >
    <input type="hidden" id="file" value="<?php echo $file ?>">
    <input type="hidden" id="check_id" value="<?php echo $check_id ?>">
    <input type="hidden" id="attach_id" value="<?php echo $attach_id ?>">
    <?php
        $check_model = RfList::model()->findByPk($check_id);
    ?>
    <?php
        if($check_model->status == '0' || $check_model->status == '5' && $tag == '0') {
    ?>
            <button onclick="capture()" type="button" class="btn btn-default btn-lg" style="margin-left: 10px" >Annoate</button>
            <button onclick="save()" type="button" class="btn btn-default btn-lg" style="margin-left: 10px" >Save</button>
    <?php
        }
    ?>
<!--    <button id="sign_clear">清除</button>-->
<!--    <button id="save">保存</button>-->
    <input id="face_src" type="hidden">
    <div id="pic" style="width: 100%;overflow-y: scroll">
        <table class="table-striped" style="width: 80%;margin-left: 20px;margin-top: 20px">
            <tr height="35px">
                <th style="padding-left: 15px;">Image</th>
                <th>Remarks</th>
                <th>Updated On</th>
                <th>Page</th>
            </tr>
            <?php
            if(!empty($detail_list)) {
                foreach ($detail_list as $item => $value) {
                    ?>
                    <tr >
                        <td>
                            <a onclick="detail('<?php echo $value['note_id'] ?>','<?php echo $value['pic'] ?>','<?php echo $tag ?>')" class="thumbnail">
                                <img width="105" height="70" src="<?php echo $value['pic'] ?>">
                            </a>
                        </td>
                        <td><?php echo $value['remark'] ?></td>
                        <td><?php echo Utils::DateToEn($value['record_time']) ?></td>
                        <td><?php echo $value['page'] ?></td>
                    </tr>
                    <?php
                }
            }?>
        </table>
    </div>
</div>
<div id="printContainer"></div>
<script type="text/javascript" src="js/html2canvas.js"></script>
<script src="js/jquery-2.1.1.min.js"></script>
<script src="js/my.js" type="text/javascript"></script>
<script>
    function canvas2Image(canvas, width, height) {
        const retCanvas = document.createElement('canvas');
        const retCtx = retCanvas.getContext('2d');
        retCanvas.width = width;
        retCanvas.height = height;
        retCtx.drawImage(canvas, 0, 0, width, height, 0, 0, width, height);
        const img = document.createElement('img');
        img.src = retCanvas.toDataURL('image/jpeg');  // 可以根据需要更改格式
        return img;
    }
    function capture(){
        var pagenumber = $('#pageNumber').val();
        html2canvas(document.querySelector("#viewerContainer")).then(canvas => {

            const context = canvas.getContext('2d');
            canvas.id = 'canvasEdit';
            // 关闭抗锯齿形
            context.mozImageSmoothingEnabled = false;
            context.webkitImageSmoothingEnabled = false;
            context.msImageSmoothingEnabled = false;
            context.imageSmoothingEnabled = false;
            context.scale(2, 2);
            // canvas转化为图片
            const img = canvas2Image(canvas, canvas.width, canvas.height);
//            document.getElementById('pic').innerHTML = '';
//            document.getElementById("pic").appendChild(canvas);
            dealImage(canvas,pagenumber);
//            $(document).esign("canvasEdit", "sign_show", "sign_clear", "sign_ok");
//            $(document).on('click', '#save', dealImage);
//                img.style.cssText = "width:40%;height:100%;position:absolute;top:0;right:0;bottom:0;opacity:1;";

            //document.body.appendChild(canvas); //canvas
        });
    }

    function dealImage(canvas,pagenumber)
    {
        //生成canvas
//        var canvas = document.getElementById("canvasEdit");
//        var ctx = canvas.getContext('2d');
        // 图像质量
        quality = 0.9;
        // quality值越小，所绘制出的图像越模糊
        var base64 = canvas.toDataURL();
        // 生成结果
        var result = {
            base64 : base64,
            clearBase64 : base64.substr(base64.indexOf(',') + 1)
        };
        var form = document.forms[0];

        var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数

        //convertBase64UrlToBlob函数是将base64编码转换为Blob
        formData.append("file1", convertBase64UrlToBlob(base64), 'sign'+'.jpg');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
        $.ajax({
            url: 'https://shell.cmstech.sg/appupload',
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // 告诉jQuery不要去处理发送的数据
            contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        var file_src = value.file1;
                        $("#face_src").val(value.file1);
                        var attach_id = $("#attach_id").val();
                        var check_id = $("#check_id").val();
                        sessionStorage.setItem('pic', file_src);
                        var check_id = $('#check_id').val();
                        var attach_id = $('#attach_id').val();
                        var file = $('#file').val();
                        window.location = "./index.php?r=rf/rf/annotatelist&pic_path="+file_src+"&attach_id="+attach_id+"&check_id="+check_id+"&pagenumber="+pagenumber;
                    }
                });
            },
            /*xhr:function(){            //在jquery函数中直接使用ajax的XMLHttpRequest对象
             var xhr = new XMLHttpRequest();

             xhr.upload.addEventListener("progress", function(evt){
             if (evt.lengthComputable) {
             var percentComplete = Math.round(evt.loaded * 100 / evt.total);
             console.log("正在提交."+percentComplete.toString() + '%');        //在控制台打印上传进度
             }
             }, false);

             return xhr;
             }*/
        });
    }

    /**
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     *            用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){

        var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte

        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }
    
    function detail(note_id,pic,tag) {
        var file = $('#file').val();
        window.location = "./index.php?r=rf/rf/annotateinfo&note_id="+note_id+"&file="+file+"&pic="+pic+"&tag="+tag;
    }

    function save(){
        window.close();
    }
</script>


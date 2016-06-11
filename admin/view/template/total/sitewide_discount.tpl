<?php

/**
 * Sitewide (Global) Discount extension for Opencart.
 *
 * @author Anthony Lawrence <freelancer@anthonylawrence.me.uk>
 * @version 1.0
 * @copyright © Anthony Lawrence 2011
 * @license Creative Common's ShareAlike License - http://creativecommons.org/licenses/by-sa/3.0/
 */


?>

<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><?php echo $entry_total; ?></td>
                        <td><input type="text" name="sitewide_discount_total" value="<?php echo $sitewide_discount_total; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_amount; ?></td>
                        <td><input type="text" name="sitewide_discount_amount" value="<?php echo $sitewide_discount_amount; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_type; ?></td>
                        <td>
                            <select name="sitewide_discount_type" id="sitewide_discount_type">
                                <option value="F" <?= (($sitewide_discount_type == "F" ? "selected='selected'" : "")) ?>>Fixed Value</option>
                                <option value="P" <?= (($sitewide_discount_type == "P" ? "selected='selected'" : "")) ?>>Percentage</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_date_start; ?></td>
                        <td><input type="text" name="sitewide_discount_date_start" id="date_start" value="<?php echo $sitewide_discount_date_start; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_date_end; ?></td>
                        <td><input type="text" name="sitewide_discount_date_end" id="date_end" value="<?php echo $sitewide_discount_date_end; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="sitewide_discount_status">
                                <?php if ($sitewide_discount_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="sitewide_discount_sort_order" value="<?php echo $sitewide_discount_sort_order; ?>" size="1" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <script type="text/javascript"><!--
        $('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
        $('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
    //--></script>

    <?php echo $footer; ?>
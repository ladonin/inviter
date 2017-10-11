<?php
$buffer = $buffer ?? false;
$push_link = $push_link ?? true;

$link = prepare_link_to_user($user['profile_id']);
$type_images = '';
$user_types = get_types_array($user);
$data_array = json_decode($user['data'], true);
$count_imported_type_links = '';
foreach ($user_types as $user_type) {
    $user_type_link = '';
    if ($is_loaded_status == 1) {
        if ($user_type == 2) {
            if (!empty($data_array['group_id'])) {
                //$user_type_link = constant('GROUP_URL_' . strtoupper($net_code)) . $data_array['group_id'];
            }
        }
    } else {
        if (!empty($data_array['urls'][$user_type])) {
            $user_type_link = 'user_data/' . $net_code . '/' . $show_imported_categories . '/' . $user['profile_id'];
            $count_imported_type_links = count($data_array['urls'][$user_type]);
        }
    }

    if ($user_type_link) {
        $type_images .= '<a class="pointer user_data_type_link" data-url="' . $user_type_link . '">';
    }
    $type_images .= '<span style="white-space: nowrap;margin-right:10px; margin-bottom:0px;"><img style="margin-right:5px; margin-top:10px;" src="/img/' . get_type_code_by_id($user_type) . '.png" width="25" data-toggle="tooltip" data-placement="top" title="' . get_type_name_by_id($user_type) . '"><span style="position:relative; top:6px;">' . $count_imported_type_links . '</span></span>';
    if ($user_type_link) {
        $type_images .= '</a> ';
    }
}
$type_comments = '';
$data_comments = (!empty($data_array['comments'])) ? $data_array['comments'] : array();
foreach ($data_comments as $key_type => $data_comment) {
    $type_comments .= '<div style="padding-top:5px">';
    if (count($user_types) > 1) {
        $type_comments .= '<img src="/img/' . get_type_code_by_id($key_type) . '.png" width="20" style="margin-right:10px;" data-toggle="tooltip" data-placement="top" title="' . get_type_name_by_id($key_type) . '">';
    }
    $type_comments .= '<span style="color:#333">' . $data_comment . '</span></div>';
}
$avatar = $user['user_avatar'] ? $user['user_avatar'] : '/img/no-photo.png';
$border_color = $from_collection_status ? '#4380be' : '#bc6060';
if ($buffer === true) {
    ob_start();
}
?>
<div class="row" style="border-left: 5px solid <?= $border_color ?>;">
    <div class="col-xs-10" style="padding-left:0">
        <div class="list-group-item" target="_blank" style="
             border-radius: 0;
             border: 0;
             padding: 10px;
             ">
            <div class="media">
                <div class="media-left" style="position: relative;">
                    <div class="user_seen_label" data-toggle="tooltip" data-container="body" data-placement="top" title="Просмотрен"><i class="glyphicon glyphicon-ok" style="/*! width: 30px; */"></i></div>
                    <a href="<?= $link; ?>" class="user_link" onclick="window.open('<?= $link; ?>', '_blank', 'left=300, top=100, width=1000, height=800');
                                                    return false;">
                        <img class="media-object" style="width:60px; border-radius:5px;" src="<?= $avatar; ?>" title="<?= $user['user_fio']; ?>">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading mb-0" style="margin-top: 0px;"><a class="user_link" style="line-height:21px" href="<?= $link; ?>" onclick="window.open('<?= $link; ?>', '_blank', 'left=300, top=100, width=900, height=800');
                                                    return false;"><?= ($user['user_fio'] ? unescapeUTF8EscapeSeq($user['user_fio']) : $user['profile_id']); ?></a></h4>
                    <?= $type_images; ?>
                    <?php if ($type_comments) { ?>
                        <div class="note_comments well pl-10 pr-10 pb-10 pt-5" style="margin-top:10px; display:none; background-color: #f1f5f8;">
                            <?= $type_comments; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-2 text-right" style="padding-right:10px; padding-top:10px">
        <div style="height:25px">
        <?php if ($type_comments) { ?>
            <img class="note_comment_icon" style="cursor:pointer" src="/img/note_1.png" width="25">
        <?php } ?>
        </div>
        <div>
            <?php
            if (isset($ids_not_invited_array) && !in_array($user['id'],$ids_not_invited_array)) {?>


            <i class="glyphicon glyphicon-ok mt-10" style="color:#449d44" data-toggle="tooltip" data-placement="left" title="Приглашен"></i>


            <?php } ?>
        </div>






    </div>
</div>
<div style="border-top:1px solid #ddd; margin:10px 0"></div>
<?php
if ($buffer === true) {
    $_SESSION[$net_code]['last_viewed_users'][] = ob_get_contents();
}
if ($push_link) {
?>
<script>links.push('<?= $link; ?>');</script><?php
}
if ($buffer === true) {
    ob_end_flush();
}



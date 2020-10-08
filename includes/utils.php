<?php

function check_to_see_if_more_tag_is_set($post) {
    $extended_content = get_extended( $post->post_content );
    $more_is_being_used = $extended_content['extended'] ? true : false;

    return $more_is_being_used || strpos($post->post_content, "<span id=\"more-" . $post->ID . "\"></span>") !== false;
}

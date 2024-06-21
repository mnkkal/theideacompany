<script id="getChatRequestTmpl" type="text/x-jsrender">
<div class="request__wrapper">
    <div class="request__header">
        <div class="profile-image">
            <img src="{{:photo_url}}">
        </div>
    </div>
    {{if ~checkReqAlreadyDeclined(chat_req)}}
        <div class="request__content">
            <h3 class="request__content-title"><?php echo __('messages.new_keys.you_declined_your_chat_for_this_user') ?></h3>
        </div>
    {{else}}
        <div class="request__content">
            <h3 class="request__content-title"><?php echo __('messages.new_keys.your_private_conservation'); ?> {{:name}}.</h3>
            <span class="text-muted mt-3">{{:name}} <?php echo __('messages.new_keys.wants_to_chat_with_you'); ?> </span>
        </div>
        <div class="request__message">
            <h5 class="text-muted"><?php echo __('messages.new_keys.join_private_conversation') ?></h5>
            <div class="request__buttons">
                <a class="decline-btn" id="declineChatReq" data-id={{:chat_req_id}}><?php echo __('messages.new_keys.decline'); ?></a>
                <a class="accept-btn" id="acceptChatReq" data-id={{:chat_req_id}}><?php echo __('messages.new_keys.accept'); ?></a>
            </div>
        </div>
    {{/if}}
</div>

</script>

<script id="sendRequestTmpl" type="text/x-jsrender">
<div class="request__wrapper">
    <div class="request__header">
        <div class="profile-image">
            <img src="{{:photo_url}}">
        </div>
    </div>
    {{if ~checkReqAlreadySent(chat_req)}}
        <div class="request__content">
            <h3 class="request__content-title"><?php echo __('messages.new_keys.you_have_send_request_to_this_user') ?></h3>
        </div>
    {{else}}
        <div class="request__content">
            <h3 class="request__content-title"><?php echo __('messages.new_keys.your_private_conservation'); ?> {{:name}}.</h3>
        </div>
        <div class="send__request__message text-center">
            <h5 class="text-muted"><?php echo __('messages.new_keys.start_conversation_with') ?> {{:name}} <?php echo __('messages.new_keys.now') ?></h5>
            <input type="text" placeholder="<?php echo __('messages.new_keys.placeholder_messages') ?>" id="chatRequestMessage-{{:id}}">
            <div class="mt-5 text-center">
                <a id="sendChatRequest" class="send-request-btn" data-id={{:id}}><?php echo __('messages.new_keys.send_chat_request') ?></a>
            </div>
        </div>
    {{/if}}
</div>

</script>

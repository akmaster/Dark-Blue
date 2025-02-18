<?php
/**
 * The template for displaying comments
 *
 * @package Dark-Blue
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <i class="far fa-comments"></i>
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                echo '1 Okuyucu Yorumu';
            } else {
                echo $comment_count . ' Okuyucu Yorumu';
            }
            ?>
        </h2>

        <ul class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ul',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => 'dark_blue_comment_template'
            ));
            ?>
        </ul>

        <?php
        the_comments_navigation();

        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Bu haber için yorumlar kapalı.', 'dark-blue'); ?></p>
            <?php
        endif;
    endif;

    // Yorum teşvik mesajı
    if (comments_open()) : ?>
        <div class="comment-incentive">
            <h3><i class="fas fa-newspaper"></i> Tartışmaya Katılın</h3>
            <p>Bu haber hakkında ne düşünüyorsunuz? Görüşlerinizi diğer okuyucularla paylaşın.</p>
            <div class="benefits">
                <div class="benefit-item">
                    <i class="fas fa-comments"></i>
                    <span>Haberi tartışın</span>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Ek bilgi paylaşın</span>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-balance-scale"></i>
                    <span>Farklı bakış açıları sunun</span>
                </div>
            </div>
        </div>

        <div class="comment-guidelines">
            <h4><i class="fas fa-exclamation-circle"></i> Yorum Kuralları</h4>
            <ul>
                <li>Hakaret, küfür ve nefret söylemi içeren yorumlar onaylanmayacaktır.</li>
                <li>Kişisel bilgiler ve spam içerikli yorumlar yayınlanmayacaktır.</li>
                <li>Yorumlarınız moderatör onayından sonra yayınlanacaktır.</li>
            </ul>
        </div>
    <?php endif;

    // Yorum formu
    $commenter = wp_get_current_commenter();
    $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
    
    comment_form(array(
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title"><i class="far fa-edit"></i>',
        'title_reply'        => 'Yorum Ekle',
        'title_reply_after'  => '</h3>',
        'class_submit'       => 'submit',
        'submit_button'      => '<button type="submit" name="%1$s" id="%2$s" class="%3$s"><i class="far fa-paper-plane"></i> Yorumu Gönder</button>',
        'submit_field'       => '<div class="form-submit">%1$s %2$s</div>',
        'comment_field'      => '<div class="comment-form-comment">
                                    <label for="comment">' . _x('Yorumunuz', 'noun', 'dark-blue') . ' <span class="required">*</span></label>
                                    <textarea id="comment" name="comment" rows="5" placeholder="Bu haber hakkındaki düşüncelerinizi paylaşın..." required></textarea>
                                </div>',
        'fields'            => array(
            'author' => '<div class="comment-form-author">
                            <label for="author">' . __('İsim', 'dark-blue') . ' <span class="required">*</span></label>
                            <input id="author" name="author" type="text" placeholder="Adınız" value="' . esc_attr($commenter['comment_author']) . '" required />
                        </div>',
            'email'  => '<div class="comment-form-email">
                            <label for="email">' . __('E-posta', 'dark-blue') . ' <span class="required">*</span></label>
                            <input id="email" name="email" type="email" placeholder="E-posta adresiniz (yayınlanmayacak)" value="' . esc_attr($commenter['comment_author_email']) . '" required />
                        </div>',
            'cookies' => '<div class="comment-form-cookies-consent">
                            <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />
                            <label for="wp-comment-cookies-consent">' . __('Bir dahaki sefere yorum yaptığımda kullanılmak üzere adımı ve e-posta adresimi bu tarayıcıya kaydet.', 'dark-blue') . '</label>
                        </div>',
        ),
        'comment_notes_before' => '<p class="comment-notes"><i class="fas fa-info-circle"></i> ' . __('E-posta adresiniz yayınlanmayacak. Yorumunuz onaylandıktan sonra yayınlanacaktır.', 'dark-blue') . '</p>',
    ));
    ?>
</div>

<?php
// Yorum şablonu fonksiyonu
function dark_blue_comment_template($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo get_avatar($comment, $args['avatar_size']); ?>
                    <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
                    <?php if (user_can($comment->user_id, 'administrator')) : ?>
                        <span class="admin-badge"><i class="fas fa-check-circle"></i> Editör</span>
                    <?php endif; ?>
                </div>

                <div class="comment-metadata">
                    <i class="far fa-clock"></i>
                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                        <?php
                        printf(
                            _x('%1$s önce', '1: date', 'dark-blue'),
                            human_time_diff(get_comment_time('U'), current_time('timestamp'))
                        );
                        ?>
                    </a>
                </div>
            </div>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <div class="comment-actions">
                <div class="reply">
                    <?php
                    comment_reply_link(array_merge($args, array(
                        'add_below'  => 'div-comment',
                        'depth'      => $depth,
                        'max_depth'  => $args['max_depth'],
                        'before'     => '<i class="fas fa-reply"></i> ',
                        'reply_text' => 'Yanıtla'
                    )));
                    ?>
                </div>
                <?php if (current_user_can('edit_comment', $comment->comment_ID)) : ?>
                    <a href="<?php echo get_edit_comment_link(); ?>" class="comment-edit-link">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                <?php endif; ?>
            </div>
        </article>
    </li>
    <?php
}
?> 
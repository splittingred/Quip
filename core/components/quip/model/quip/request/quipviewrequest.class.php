<?php
/**
 * @package quip
 */
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Runs the comment front-end system
 *
 * @package quip
 */
class QuipViewRequest extends modRequest {
    public $quip = null;

    function __construct(Quip &$quip) {
        parent :: __construct($quip->modx);
        $this->quip =& $quip;
    }

    public function handle() {
        $output = '';

        if (empty($this->quip->config['thread'])) {
            return '';
        }

        $this->modx->regClientCSS($this->quip->config['css_url'].'web.css');
        $this->modx->regClientStartupScript($this->quip->config['js_url'].'web/quip.js');
        $this->modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        Quip.config = {
            connector: "'.$this->quip->config['connector_url'].'"
            ,resource: "'.$this->modx->resource->get('id').'"
            ,ctx: "'.$this->modx->context->get('key').'"
        };
        </script>');


        $c = $this->modx->newQuery('quipComment');
        $c->innerJoin('modUser','Author');
        $c->where(array(
            'quipComment.thread' => $this->quip->config['thread'],
        ));
        $c->select('quipComment.*,Author.username AS username');
        $c->sortby('quipComment.createdon','DESC');

        $comments = $this->modx->getCollection('quipComment',$c);
        $commentsChunk = '';
        $alt = false;
        foreach ($comments as $comment) {
            $cp = $comment->toArray('quip.com.',true);
            if ($alt) { $cp['quip.com.alt'] = 'quip-comment-alt'; }
            $dateFormat = $this->modx->getOption('dateFormat',$this->quip->config,'%b %d, %Y at %I:%M %p');
            $cp['quip.com.createdon'] = strftime($dateFormat,strtotime($comment->get('createdon')));

            if ($this->modx->user->isAuthenticated() || $this->modx->getOption('debug',$this->quip->config,false)) {
                if ($comment->get('author') == $this->modx->user->get('id')) {
                    $cp['quip.com.options'] = $this->quip->getChunk('quipCommentOptions',array(
                        'quip.comopt.id' => $comment->get('id'),
                    ));
                } else {
                    $cp['quip.com.options'] = '';
                }

                $cp['quip.com.report'] = $this->quip->getChunk('quipReport',array(
                    'quip.comrep.id' => $comment->get('id'),
                ));
            } else {
                $cp['quip.com.report'] = '';
            }

            $commentsChunk .= $this->quip->getChunk('quipComment',$cp);
            $alt = !$alt;
        }

        $addCommentChunk = '';
        if (($this->modx->user->isAuthenticated() || $this->modx->getOption('debug',$this->quip->config,false))
            && !$this->modx->getOption('closed',$this->quip->config,false)) {
            $selfurl = '';
            $addCommentChunk = $this->quip->getChunk('quipAddComment',array(
                'quip.self' => $selfurl,
                'quip.username' => $this->modx->user->get('username'),
                'quip.thread' => $this->quip->config['thread'],
            ));
        } else {
            $addCommentChunk = $this->quip->getChunk('quipLoginToComment',array());
        }

        $output = $this->quip->getChunk('quipComments',array(
            'quip.comments' => $commentsChunk,
            'quip.addcomment' => $addCommentChunk,
        ));

        return $output;
    }

}
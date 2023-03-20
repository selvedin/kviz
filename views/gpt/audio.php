<?php
$this->title = __('Audio');
?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title"><?= __('Audio') ?></div>
      </div>
      <div class="card-body">
        <div id="recoreder">
          <div class="audio-recording-container">
            <h1 class="title"><?= __('Press a microphone to start recording') ?></h1>
            <i class="start-recording-button fa fa-microphone" aria-hidden="true"></i>
            <div class="recording-contorl-buttons-container hide">
              <i class="cancel-recording-button fa fa-window-close" aria-hidden="true"></i>
              <div class="recording-elapsed-time">
                <i class="red-recording-dot fa fa-circle" aria-hidden="true"></i>
                <p class="elapsed-time"></p>
              </div>
              <i class="stop-recording-button fa fa-stop" aria-hidden="true"></i>
            </div>
            <div class="text-indication-of-audio-playing-container">
              <p class="text-indication-of-audio-playing hide">
                <?= __('Audio is playing') ?><span>.</span><span>.</span><span>.</span></p>
            </div>
          </div>
          <div class="overlay hide">
            <div class="browser-not-supporting-audio-recording-box">
              <p><?= __('To record audio, use browsers like Chrome and Firefox that support audio recording') ?>.</p>
              <button type="button" class="close-browser-not-supported-box"><?= __('OK') ?>.</button>
            </div>
          </div>
          <!-- Transcribed text -->
          <div class="row">
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-body">
                  <textarea id="response-transcript" class="card-text form-control">

                  </textarea>
                </div>
              </div>
            </div>
          </div>

          <audio controls class="audio-element hide">
          </audio>
        </div>
        <?= $response ?>
      </div>
    </div>
  </div>
</div>
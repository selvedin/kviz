<div v-if="question.question_type == 4" class="col-md-12 text-center">
  <div class="row">
    <div class="col-6">
      <button v-for="(pair, index) in question.pairs.left" @click="addPair(pair.id, pair.one, $event, false, index)" :disabled='!canAnswer' :class="[classObject, 'mt-4','btn-quiz-pair', getPairIndex(pair.id)!=null?COLORS[getPairIndex(pair.id)]:'btn-quiz-active']">
        {{pair.id + ' - ' + pair.one }}
      </button>
    </div>
    <div class="col-6">
      <button v-for="(pair, index) in question.pairs.right" @click="addPair(pair.id,pair.two,$event, true)" :disabled='!canAnswer' :class="[classObject, 'mt-4','btn-quiz-pair', getPairIndex(pair.id, true)!=null?COLORS[getPairIndex(pair.id, true)]:'btn-quiz-active']">
        {{pair.id + ' - ' + pair.two}}
      </button>
    </div>
  </div>
</div>
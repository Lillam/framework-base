<?php $this->extends('layouts/main'); ?>
@section("body")
    Hello there
    <?php if (1 === 1): ?>
        <p>1 does equate to 1? surprising i know</p>
    <?php endif; ?>
@endsection
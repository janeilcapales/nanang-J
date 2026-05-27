@extends('layouts.app')

@section('page-title', 'AI Dish Suggestion')

@section('content')

<div class="page-header">
    <h2>AI Dish Suggestion ✨</h2>
    <p>Tell us your taste preference and we'll suggest the perfect dish</p>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="form-card">
            <div style="text-align:center;margin-bottom:24px;">
                <div style="font-size:52px;margin-bottom:8px;">🤖</div>
                <div style="font-family:'Playfair Display',serif;font-size:18px;color:#1a1008;">What's your craving?</div>
                <div style="font-size:13px;color:#888;margin-top:4px;">Our AI will pick the best dish for you</div>
            </div>

            <form method="POST" action="{{ route('ai.process') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Taste Preference</label>
                    <select name="taste" class="form-control" style="text-align:center;font-size:15px;">
                        <option value="savory">🍖 Savory</option>
                        <option value="sour">🍋 Sour</option>
                        <option value="rich">🍛 Rich & Hearty</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="width:100%;margin-top:8px;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Suggest a Dish
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        @if(isset($suggestion))
        <div style="background:linear-gradient(135deg,#1a1008,#2d1f0f);border-radius:18px;padding:36px;text-align:center;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;">
            <div style="font-size:56px;margin-bottom:16px;">🍽️</div>
            <div style="color:#a89070;font-size:12px;letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;">We suggest</div>
            <div style="font-family:'Playfair Display',serif;font-size:32px;color:white;font-weight:700;margin-bottom:16px;">
                {{ $suggestion }}
            </div>
            <div style="color:#a89070;font-size:13px;max-width:280px;line-height:1.7;">
                Based on your taste preference, this dish is our top pick for you today.
            </div>
            <a href="{{ route('reservations.create') }}" class="btn-warning" style="margin-top:24px;">
                <i class="fa-solid fa-calendar-check"></i> Reserve with this Dish
            </a>
        </div>
        @else
        <div style="background:#f5ede0;border-radius:18px;padding:36px;text-align:center;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:260px;">
            <div style="font-size:48px;margin-bottom:12px;">👈</div>
            <div style="font-family:'Playfair Display',serif;font-size:20px;color:#555;margin-bottom:6px;">Pick your taste</div>
            <div style="font-size:13px;color:#aaa;">Your AI dish suggestion will appear here</div>
        </div>
        @endif
    </div>
</div>

@endsection
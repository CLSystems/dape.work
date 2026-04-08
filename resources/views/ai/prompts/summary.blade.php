=== INSTRUCTIONS ===
{{ $instructions }}
=== END INSTRUCTIONS ===

=== DATA ===
Page type: {{ $type }}
Identifier: {{ $id }}

Statistics:
{!! json_encode($stats, JSON_PRETTY_PRINT) !!}
=== END DATA ===

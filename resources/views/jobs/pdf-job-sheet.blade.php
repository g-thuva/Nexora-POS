<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Sheet - {{ $job->reference_number }}</title>
    <style>
        @php
            $letterheadConfig = $letterheadConfig ?? [];
            $hasLetterhead = isset($letterheadConfig['letterhead_file']) && $letterheadConfig['letterhead_file'];
            $positions = $letterheadConfig['positions'] ?? [];

            // Convert positions to the format expected by the template
            // Positions are saved as: { 'job_reference': { x: 50, y: 100, fontSize: 11 }, ... }
            $positionMap = [];
            if (is_array($positions)) {
                foreach ($positions as $fieldKey => $pos) {
                    if (is_array($pos)) {
                        $positionMap[$fieldKey] = [
                            'x' => $pos['x'] ?? 50,
                            'y' => $pos['y'] ?? 100,
                            'font_size' => $pos['fontSize'] ?? 11,
                            'font_weight' => $pos['font_weight'] ?? 'normal',
                        ];
                    }
                }
            }

            $canvasWidth = 595;
            $marginLeft = 25;
            $marginRight = 25;
            $totalMargins = $marginLeft + $marginRight;
            $perfectTableWidth = $canvasWidth - $totalMargins;

            $itemsAlignment = $letterheadConfig['items_alignment'] ?? [];
            $forceBalancedMargins = true;
            $balancedStartX = $marginLeft;
            $balancedTableWidth = $perfectTableWidth;
        @endphp

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A5 portrait;
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 8px;
            line-height: 1.2;
            color: #000;
            background: white;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 148mm;
            height: 210mm;
            position: relative;
            margin: 0;
            padding: 0;
            overflow: hidden;
            @if($hasLetterhead)
                @php
                    $preview = $letterheadConfig['preview_image'] ?? null;
                    if (!empty($letterheadConfig['preview_image_data'])) {
                        $bgAsset = $letterheadConfig['preview_image_data'];
                    } else {
                        $bgAsset = $preview ? asset('letterheads/' . $preview) : asset('letterheads/' . $letterheadConfig['letterhead_file']);
                    }
                @endphp
                background-image: url('{{ $bgAsset }}');
                background-size: 100% 100%;
                background-repeat: no-repeat;
                background-position: top left;
            @else
                background: white;
            @endif
        }

        .positioned-element {
            position: absolute;
            font-family: Arial, sans-serif;
            z-index: 1;
        }

        .items-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        .items-table th {
            background: #f5f5f5;
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        .items-table td {
            border: 1px solid #333;
            padding: 6px 10px;
            font-size: 10px;
            vertical-align: top;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #ddd;
        }

        .info-table .label {
            font-weight: bold;
            width: 35%;
            color: #333;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="page">
        @if($hasLetterhead)
            {{-- If controller provided embedded preview data use it as an IMG so DomPDF doesn't need remote fetches --}}
            @php $previewData = $letterheadConfig['preview_image_data'] ?? null; @endphp
            @if(!empty($previewData))
                <img src="{{ $previewData }}" alt="letterhead-preview" style="position:absolute; left:0; top:0; width:100%; height:100%; z-index:0;" />
            @endif

            {{-- Job Reference --}}
            @if(isset($positionMap['job_reference']))
            <div class="positioned-element" style="
                left: {{ $positionMap['job_reference']['x'] ?? 50 }}px;
                top: {{ $positionMap['job_reference']['y'] ?? 100 }}px;
                font-size: {{ $positionMap['job_reference']['font_size'] ?? 11 }}px;
                font-weight: {{ $positionMap['job_reference']['font_weight'] ?? 'bold' }};
            ">
                JOB: {{ $job->reference_number }}
            </div>
            @endif

            {{-- Customer Name --}}
            @if(isset($positionMap['customer_name']))
            <div class="positioned-element" style="
                left: {{ $positionMap['customer_name']['x'] ?? 50 }}px;
                top: {{ $positionMap['customer_name']['y'] ?? 150 }}px;
                font-size: {{ $positionMap['customer_name']['font_size'] ?? 11 }}px;
                font-weight: {{ $positionMap['customer_name']['font_weight'] ?? 'bold' }};
            ">
                {{ $job->customer->name ?? 'N/A' }}
            </div>
            @endif

            {{-- Customer Phone --}}
            @if(isset($positionMap['customer_phone']))
            <div class="positioned-element" style="
                left: {{ $positionMap['customer_phone']['x'] ?? 50 }}px;
                top: {{ $positionMap['customer_phone']['y'] ?? 180 }}px;
                font-size: {{ $positionMap['customer_phone']['font_size'] ?? 11 }}px;
                font-weight: {{ $positionMap['customer_phone']['font_weight'] ?? 'normal' }};
            ">
                {{ $job->customer->phone ?? 'N/A' }}
            </div>
            @endif

            {{-- Customer Address --}}
            @if(isset($positionMap['customer_address']))
            <div class="positioned-element" style="
                left: {{ $positionMap['customer_address']['x'] ?? 50 }}px;
                top: {{ $positionMap['customer_address']['y'] ?? 210 }}px;
                font-size: {{ $positionMap['customer_address']['font_size'] ?? 11 }}px;
                font-weight: {{ $positionMap['customer_address']['font_weight'] ?? 'normal' }};
                line-height: 1.4;
                max-width: 300px;
            ">
                {{ $job->customer->address ?? 'N/A' }}
            </div>
            @endif

            {{-- Job Type --}}
            @if(isset($positionMap['job_type']))
            <div class="positioned-element" style="
                left: {{ $positionMap['job_type']['x'] ?? 400 }}px;
                top: {{ $positionMap['job_type']['y'] ?? 100 }}px;
                font-size: {{ $positionMap['job_type']['font_size'] ?? 11 }}px;
                font-weight: {{ $positionMap['job_type']['font_weight'] ?? 'normal' }};
            ">
                Type: {{ $job->jobType->name ?? $job->type ?? 'N/A' }}
            </div>
            @endif

            {{-- Estimated Duration --}}
            @if(isset($positionMap['estimated_duration']))
            <div class="positioned-element" style="
                left: {{ $positionMap['estimated_duration']['x'] ?? 400 }}px;
                top: {{ $positionMap['estimated_duration']['y'] ?? 130 }}px;
                font-size: {{ $positionMap['estimated_duration']['font_size'] ?? 11 }}px;
                font-weight: {{ $positionMap['estimated_duration']['font_weight'] ?? 'normal' }};
            ">
                Duration: {{ $job->estimated_duration ? $job->estimated_duration . ' days' : 'N/A' }}
            </div>
            @endif

            {{-- Status --}}
            @if(isset($positionMap['status']))
            <div class="positioned-element" style="
                left: {{ $positionMap['status']['x'] ?? 400 }}px;
                top: {{ $positionMap['status']['y'] ?? 160 }}px;
                font-size: {{ $positionMap['status']['font_size'] ?? 11 }}px;
                font-weight: {{ $positionMap['status']['font_weight'] ?? 'normal' }};
                text-transform: capitalize;
            ">
                Status: {{ str_replace('_', ' ', $job->status) }}
            </div>
            @endif

            {{-- Description --}}
            @if(isset($positionMap['description']))
            <div class="positioned-element" style="
                left: {{ $positionMap['description']['x'] ?? 50 }}px;
                top: {{ $positionMap['description']['y'] ?? 280 }}px;
                font-size: {{ $positionMap['description']['font_size'] ?? 11 }}px;
                font-weight: {{ $positionMap['description']['font_weight'] ?? 'normal' }};
                line-height: 1.6;
                max-width: 500px;
            ">
                <strong>Description:</strong><br>
                {{ $job->description ?? 'No description provided' }}
            </div>
            @endif

            {{-- Items Table - Job Details --}}
            @if(isset($itemsAlignment['x']) && isset($itemsAlignment['y']))
                <div class="positioned-element" style="
                    left: {{ $balancedStartX }}px;
                    top: {{ $itemsAlignment['y'] }}px;
                    width: {{ $balancedTableWidth }}px;
                ">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Field</th>
                                <th style="width: 70%;">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Job Type</strong></td>
                                <td>{{ $job->jobType->name ?? $job->type ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $job->status) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Estimated Duration</strong></td>
                                <td>{{ $job->estimated_duration ? $job->estimated_duration . ' days' : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created Date</strong></td>
                                <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d/m/Y h:i A') }}</td>
                            </tr>
                            @if($job->description)
                            <tr>
                                <td style="vertical-align: top;"><strong>Description</strong></td>
                                <td style="white-space: pre-wrap;">{{ $job->description }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div style="margin-top: 30px; padding: 15px; border: 1px solid #ddd; background: #f9f9f9;">
                        <p style="font-weight: bold; margin-bottom: 10px;">Technician Notes:</p>
                        <div style="min-height: 80px; border-bottom: 1px solid #ccc; margin-bottom: 10px;"></div>
                        <div style="min-height: 80px;"></div>
                    </div>

                    <div style="margin-top: 30px; overflow: hidden;">
                        <div style="float: left; width: 48%;">
                            <p style="font-weight: bold; margin-bottom: 5px;">Technician Signature:</p>
                            <div style="border-bottom: 1px solid #000; height: 40px; margin-top: 20px;"></div>
                            <p style="margin-top: 5px; font-size: 11px;">Name: _________________</p>
                            <p style="font-size: 11px;">Date: _________________</p>
                        </div>
                        <div style="float: right; width: 48%;">
                            <p style="font-weight: bold; margin-bottom: 5px;">Customer Signature:</p>
                            <div style="border-bottom: 1px solid #000; height: 40px; margin-top: 20px;"></div>
                            <p style="margin-top: 5px; font-size: 11px;">Name: _________________</p>
                            <p style="font-size: 11px;">Date: _________________</p>
                        </div>
                    </div>
                </div>
            @endif

        @else
            {{-- No letterhead - simple layout with full A5 coverage --}}
            <div style="padding: 5mm 6mm; max-height: 210mm; overflow: hidden;">

                {{-- Shop Header --}}
                <div style="margin-bottom: 5px; border-bottom: 1px solid #000; padding-bottom: 3px;">
                    <table style="width: 100%; font-size: 7px;">
                        <tr>
                            <td style="width: 60%; vertical-align: top;">
                                <div style="font-size: 11px; font-weight: bold; color: #000; margin-bottom: 1px;">{{ $shop->name ?? 'Shop Name' }}</div>
                                @if($shop->address)
                                <div style="margin-bottom: 1px; color: #333;">{{ $shop->address }}</div>
                                @endif
                                @if($shop->phone)
                                <div style="margin-bottom: 1px; color: #333;"><strong>Phone:</strong> {{ $shop->phone }}</div>
                                @endif
                                @if($shop->email)
                                <div style="color: #333;"><strong>Email:</strong> {{ $shop->email }}</div>
                                @endif
                            </td>
                            <td style="width: 40%; vertical-align: top; text-align: right;">
                                <div style="font-size: 10px; font-weight: bold; color: #0066cc; margin-bottom: 1px;">{{ $job->reference_number }}</div>
                                <div style="font-size: 6px; color: #666;">Date: {{ $job->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: center; margin-bottom: 4px;">
                    <h1 style="font-size: 12px; margin: 0; font-weight: bold;">JOB SHEET</h1>
                </div>

                {{-- Customer Information --}}
                @if($job->customer)
                <div style="margin-bottom: 4px; padding: 3px; border: 1px solid #000; background: #f9f9f9;">
                    <div style="font-weight: bold; margin-bottom: 2px; font-size: 7px;">CUSTOMER INFORMATION</div>
                    <table style="width: 100%; font-size: 6px;">
                        <tr>
                            <td style="width: 12%; font-weight: bold; padding: 1px 0;">Name:</td>
                            <td style="width: 38%; padding: 1px 0;">{{ $job->customer->name }}</td>
                            <td style="width: 12%; font-weight: bold; padding: 1px 0;">Phone:</td>
                            <td style="width: 38%; padding: 1px 0;">{{ $job->customer->phone ?? 'N/A' }}</td>
                        </tr>
                        @if($job->customer->address)
                        <tr>
                            <td style="font-weight: bold; padding: 1px 0; vertical-align: top;">Address:</td>
                            <td colspan="3" style="padding: 1px 0;">{{ $job->customer->address }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                @endif

                {{-- Job Details --}}
                <div style="margin-bottom: 4px;">
                    <div style="font-weight: bold; margin-bottom: 2px; font-size: 7px; border-bottom: 1px solid #000; padding-bottom: 1px;">JOB DETAILS</div>
                    <table style="width: 100%; font-size: 6px; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25%; padding: 2px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Job Type:</td>
                            <td style="width: 25%; padding: 2px; border: 1px solid #ddd;">{{ $job->jobType->name ?? $job->type ?? 'N/A' }}</td>
                            <td style="width: 25%; padding: 2px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Status:</td>
                            <td style="width: 25%; padding: 2px; border: 1px solid #ddd; text-transform: capitalize; font-weight: bold;">{{ str_replace('_', ' ', $job->status) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Duration:</td>
                            <td style="padding: 2px; border: 1px solid #ddd;">{{ $job->estimated_duration ? $job->estimated_duration . ' days' : 'N/A' }}</td>
                            <td style="padding: 2px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Created:</td>
                            <td style="padding: 2px; border: 1px solid #ddd;">{{ $job->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($job->description)
                        <tr>
                            <td style="padding: 2px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; vertical-align: top;">Description:</td>
                            <td colspan="3" style="padding: 2px; border: 1px solid #ddd; line-height: 1.3;">{{ Str::limit($job->description, 200) }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                {{-- Work/Technician Notes Section --}}
                <div style="margin-top: 4px; border: 1px solid #000; padding: 3px;">
                    <div style="font-weight: bold; margin-bottom: 2px; font-size: 7px;">WORK PERFORMED / NOTES:</div>
                    <div style="border-bottom: 1px dotted #999; min-height: 18px; margin-bottom: 2px;"></div>
                    <div style="border-bottom: 1px dotted #999; min-height: 18px; margin-bottom: 2px;"></div>
                    <div style="min-height: 18px;"></div>
                </div>

                {{-- Parts/Materials Used --}}
                <div style="margin-top: 4px; border: 1px solid #666; padding: 3px;">
                    <div style="font-weight: bold; margin-bottom: 2px; font-size: 7px;">PARTS/MATERIALS:</div>
                    <div style="border-bottom: 1px dotted #ccc; min-height: 15px; margin-bottom: 1px;"></div>
                    <div style="min-height: 15px;"></div>
                </div>

                {{-- Signature Section --}}
                <div style="margin-top: 5px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 48%; vertical-align: top;">
                                <div style="border: 1px solid #000; padding: 3px; height: 35px;">
                                    <div style="font-weight: bold; font-size: 7px; margin-bottom: 10px;">Technician:</div>
                                    <div style="border-top: 1px solid #000; margin-top: 10px; padding-top: 1px;">
                                        <div style="font-size: 5px; color: #666;">Name: ___________  Date: ____</div>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 4%;"></td>
                            <td style="width: 48%; vertical-align: top;">
                                <div style="border: 1px solid #000; padding: 3px; height: 35px;">
                                    <div style="font-weight: bold; font-size: 7px; margin-bottom: 10px;">Customer:</div>
                                    <div style="border-top: 1px solid #000; margin-top: 10px; padding-top: 1px;">
                                        <div style="font-size: 5px; color: #666;">Name: ___________  Date: ____</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Footer --}}
                <div style="margin-top: 4px; text-align: center; font-size: 5px; color: #666; border-top: 1px solid #ddd; padding-top: 2px;">
                    <p style="margin: 0;">Computer-generated document</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>

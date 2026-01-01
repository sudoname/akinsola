<?php

namespace App\Filament\Pages;

use App\Models\MemorialSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class MemorialSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.memorial-settings';

    protected static ?string $navigationLabel = 'Parent Photos';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $memorial = MemorialSetting::current();
        $this->form->fill([
            'mother_photo' => $memorial->mother_photo,
            'father_photo' => $memorial->father_photo,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Memorial Page Photos')
                    ->description('Upload photos of your parents for the memorial page')
                    ->schema([
                        Forms\Components\FileUpload::make('mother_photo')
                            ->label("Mother's Photo")
                            ->image()
                            ->disk('public')
                            ->directory('memorial')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('800')
                            ->helperText('Recommended: Square image, 800x800 pixels. Will be displayed on the memorial page.')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('father_photo')
                            ->label("Father's Photo")
                            ->image()
                            ->disk('public')
                            ->directory('memorial')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('800')
                            ->helperText('Recommended: Square image, 800x800 pixels. Will be displayed on the memorial page.')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        \Log::info('Memorial Settings Save Data:', $data);

        $memorial = MemorialSetting::current();
        $memorial->update($data);

        \Log::info('Memorial After Update:', $memorial->toArray());

        Notification::make()
            ->success()
            ->title('Saved')
            ->body('Memorial photos have been updated successfully.')
            ->send();
    }
}

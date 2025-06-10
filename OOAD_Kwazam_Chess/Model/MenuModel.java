// Soukmaed Ong Yu Kang

// This class is use to store the state of the menu.

// This class is part of the Model in the MVC design pattern.
// Explanation: The model is the core functional part of the program.
// They encapsulate the game's data and the logic of how the game is played.

package Model;

public class MenuModel {

    private boolean isSoundEffectsOn = true;
    private boolean isMusicOn = true;

    // Toggle the sound effects
    public void toggleSoundEffects() {
        isSoundEffectsOn = !isSoundEffectsOn;
    }

    // Toggle the music
    public void toggleMusic() {
        isMusicOn = !isMusicOn;
    }

    // Get the state of the sound effects
    public boolean getSoundEffectsOn() {
        return isSoundEffectsOn;
    }

    // Get the state of the music
    public boolean getMusicOn() {
        return isMusicOn;
    }
}
